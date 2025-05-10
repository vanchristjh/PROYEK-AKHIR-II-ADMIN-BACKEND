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
        const submitButton = document.querySelector('button[type="submit"]');        const commentInput = document.getElementById('comment');
        const contentInput = document.getElementById('content');
        
        // Define server limits based on error message (content-length: 8855181 bytes)
        // Note: To increase the server limit, modify post_max_size in php.ini or .htaccess file
        // For PHP, add: post_max_size=16M and upload_max_filesize=16M
        const MAX_FILE_SIZE = 2; // 2MB to be safe (server limit appears to be around 8MB)
        const ALLOWED_FILE_TYPES = [
            'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip', 'application/x-zip-compressed', 'image/jpeg', 'image/png', 'image/gif', 'text/plain'
        ];
        
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
                      // Validate file size immediately
                    if (fileSize > MAX_FILE_SIZE) {
                        fileInput.value = ''; // Clear the file input
                        showError(`File terlalu besar (${fileSize}MB). Ukuran maksimal yang diizinkan adalah ${MAX_FILE_SIZE}MB. Silahkan kompres file atau gunakan layanan seperti Google Drive untuk berbagi file yang lebih besar.`);
                        submitButton.disabled = true;
                        return; // Stop processing
                    }
                    
                    // Validate file type if not empty
                    if (fileType && !ALLOWED_FILE_TYPES.includes(fileType)) {
                        fileInput.value = ''; // Clear the file input
                        showError(`Jenis file "${fileType}" tidak diizinkan. Gunakan format file yang umum seperti PDF, Word, Excel, PowerPoint, atau ZIP.`);
                        submitButton.disabled = true;
                        return; // Stop processing
                    }
                    
                    // Clear previous preview
                    filePreview.innerHTML = '';
                    
                    // Show progress container
                    progressContainer.classList.remove('hidden');
                    
                    // Determine appropriate icon based on file type
                    let iconClass = 'fa-file';
                    let colorClass = 'text-gray-500';
                    
                    if (file.type.includes('pdf')) {
                        iconClass = 'fa-file-pdf';
                        colorClass = 'text-red-500';
                    } else if (file.type.includes('word') || fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                        iconClass = 'fa-file-word';
                        colorClass = 'text-blue-500';
                    } else if (file.type.includes('spreadsheet') || fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                        iconClass = 'fa-file-excel';
                        colorClass = 'text-green-500';
                    } else if (file.type.includes('presentation') || fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) {
                        iconClass = 'fa-file-powerpoint';
                        colorClass = 'text-orange-500';
                    } else if (file.type.includes('image')) {
                        iconClass = 'fa-file-image';
                        colorClass = 'text-purple-500';
                    } else if (file.type.includes('zip') || file.type.includes('archive') || fileName.endsWith('.zip')) {
                        iconClass = 'fa-file-archive';
                        colorClass = 'text-yellow-500';
                    } else if (file.type.includes('video')) {
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
                        clearError();
                        submitButton.disabled = false;
                    });
                    
                    // File is valid
                    clearError();
                    submitButton.disabled = false;
                }
            });
        }
        
        // Form submission
        if (form) {
            form.addEventListener('submit', function(event) {
                // Additional file size validation before submission
                if (fileInput && fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
                    const fileType = file.type;
                      if (fileSize > MAX_FILE_SIZE) {
                        event.preventDefault();
                        showError(`File terlalu besar (${fileSize}MB). Ukuran maksimal yang diizinkan adalah ${MAX_FILE_SIZE}MB. Silahkan kompres file atau gunakan layanan seperti Google Drive untuk berbagi file yang lebih besar.`);
                        window.scrollTo(0, 0); // Scroll to top to show the error
                        return false;
                    }
                    
                    // Validate file type if not empty
                    if (fileType && !ALLOWED_FILE_TYPES.includes(fileType)) {
                        event.preventDefault();
                        showError(`Jenis file "${fileType}" tidak diizinkan. Gunakan format file yang umum seperti PDF, Word, Excel, PowerPoint, atau ZIP.`);
                        window.scrollTo(0, 0); // Scroll to top to show the error
                        return false;
                    }
                }
                
                // Clear auto-saved content on successful submission
                if (contentInput) {
                    localStorage.removeItem(`submission_content_${document.querySelector('input[name="assignment_id"]').value}`);
                }
                
                // Show loading state
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
                
                // Add a hidden field to track form submission progress
                const progressTracker = document.createElement('input');
                progressTracker.type = 'hidden';
                progressTracker.name = 'submission_started';
                progressTracker.value = Date.now();
                form.appendChild(progressTracker);
                
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
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${message}</p>
                        </div>
                    </div>
                </div>
            `;
            errorContainer.classList.remove('hidden');
            
            // Scroll to error
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
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