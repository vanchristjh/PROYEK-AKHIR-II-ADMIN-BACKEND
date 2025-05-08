                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('submission-form');
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('file-preview');
        const progressContainer = document.getElementById('progress-container');
        const progressBar = document.getElementById('progress-bar');
        const statusText = document.getElementById('status-text');
        const submitButton = document.querySelector('button[type="submit"]');
        const commentInput = document.getElementById('comment');
        const contentInput = document.getElementById('content');
        
        // Auto-save timer
        let autoSaveTimer;
        let lastSavedContent = '';
        
        // Initialize auto-save for text content if available
        if (contentInput) {
            // Try to restore from localStorage
            const savedContent = localStorage.getItem(`submission_content_${document.querySelector('input[name="assignment_id"]').value}`);
            if (savedContent && contentInput.value === '') {
                contentInput.value = savedContent;
            }
            
            // Setup auto-save
            contentInput.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                showStatus('Menyimpan...', 'text-yellow-600');
                
                autoSaveTimer = setTimeout(function() {
                    const content = contentInput.value;
                    if (content !== lastSavedContent) {
                        localStorage.setItem(`submission_content_${document.querySelector('input[name="assignment_id"]').value}`, content);
                        lastSavedContent = content;
                        showStatus('Tersimpan otomatis', 'text-green-600');
                        
                        // Hide the status after 3 seconds
                        setTimeout(function() {
                            hideStatus();
                        }, 3000);
                    }
                }, 1000);
            });
        }
        
        // File upload preview
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
                    const fileName = file.name;
                    const fileType = file.type;
                    
                    // Clear previous preview
                    filePreview.innerHTML = '';
                    
                    // Show progress container
                    progressContainer.classList.remove('hidden');
                    
                    // Determine appropriate icon based on file type
                    let iconClass = 'fa-file';
                    let colorClass = 'text-gray-500';
                    
                    if (fileType.includes('pdf')) {
                        iconClass = 'fa-file-pdf';
                        colorClass = 'text-red-500';
                    } else if (fileType.includes('word') || fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                        iconClass = 'fa-file-word';
                        colorClass = 'text-blue-500';
                    } else if (fileType.includes('spreadsheet') || fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                        iconClass = 'fa-file-excel';
                        colorClass = 'text-green-500';
                    } else if (fileType.includes('presentation') || fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
                        iconClass = 'fa-file-powerpoint';
                        colorClass = 'text-orange-500';
                    } else if (fileType.includes('image')) {
                        iconClass = 'fa-file-image';
                        colorClass = 'text-purple-500';
                    } else if (fileType.includes('zip') || fileType.includes('archive') || fileName.endsWith('.zip')) {
                        iconClass = 'fa-file-archive';
                        colorClass = 'text-yellow-500';
                    } else if (fileType.includes('video')) {
                        iconClass = 'fa-file-video';
                        colorClass = 'text-pink-500';
                    }
                    
                    // Create file preview element
                    const previewEl = document.createElement('div');
                    previewEl.className = 'flex items-center p-4 bg-blue-50 rounded-lg border border-blue-200 mt-2';
                    previewEl.innerHTML = `
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas ${iconClass} ${colorClass} text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs" title="${fileName}">${fileName}</div>
                            <div class="text-xs text-gray-500">${fileSize} MB</div>
                        </div>
                        <button type="button" id="remove-file" class="text-gray-400 hover:text-red-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    filePreview.appendChild(previewEl);
                    
                    // Simulate progress for visual feedback
                    simulateProgress();
                    
                    // Add remove button functionality
                    document.getElementById('remove-file').addEventListener('click', function() {
                        fileInput.value = '';
                        filePreview.innerHTML = '';
                        progressContainer.classList.add('hidden');
                        progressBar.style.width = '0%';
                    });
                    
                    // Validate file size
                    const maxSize = 20; // 20MB
                    if (file.size > maxSize * 1024 * 1024) {
                        showError(`File terlalu besar. Ukuran maksimal adalah ${maxSize}MB.`);
                        submitButton.disabled = true;
                    } else {
                        clearError();
                        submitButton.disabled = false;
                    }
                }
            });
        }
        
        // Form submission
        if (form) {
            form.addEventListener('submit', function(event) {
                // Clear auto-saved content on successful submission
                if (contentInput) {
                    localStorage.removeItem(`submission_content_${document.querySelector('input[name="assignment_id"]').value}`);
                }
                
                // Basic validation
                if (fileInput && fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const maxSize = 20; // 20MB
                    
                    if (file.size > maxSize * 1024 * 1024) {
                        event.preventDefault();
                        showError(`File terlalu besar. Ukuran maksimal adalah ${maxSize}MB.`);
                        return false;
                    }
                }
                
                // Show loading state
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
                
                // Disable form reset
                form.onreset = function(e) {
                    e.preventDefault();
                };
            });
        }
        
        // Helper functions
        function showError(message) {
            const errorContainer = document.getElementById('error-container') || createErrorContainer();
            errorContainer.innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${message}</p>
                        </div>
                    </div>
                </div>
            `;
            errorContainer.classList.remove('hidden');
        }
        
        function clearError() {
            const errorContainer = document.getElementById('error-container');
            if (errorContainer) {
                errorContainer.innerHTML = '';
                errorContainer.classList.add('hidden');
            }
        }
        
        function createErrorContainer() {
            const container = document.createElement('div');
            container.id = 'error-container';
            form.prepend(container);
            return container;
        }
        
        function simulateProgress() {
            let progress = 0;
            const interval = setInterval(function() {
                progress += Math.random() * 15;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    showStatus('File siap dikirim', 'text-green-600');
                }
                progressBar.style.width = progress + '%';
            }, 200);
        }
        
        function showStatus(message, colorClass) {
            statusText.textContent = message;
            statusText.className = colorClass + ' text-sm';
            statusText.classList.remove('hidden');
        }
        
        function hideStatus() {
            statusText.classList.add('hidden');
        }
        
        // Handle beforeunload to warn about unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (contentInput && contentInput.value !== lastSavedContent && contentInput.value.trim() !== '') {
                // Standard text will be displayed by the browser
                const confirmationMessage = 'Perubahan yang Anda buat mungkin tidak akan disimpan.';
                e.returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });
    });
</script>
@endpush