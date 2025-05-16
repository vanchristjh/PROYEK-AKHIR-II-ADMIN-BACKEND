</div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');        const filePreview = document.getElementById('filePreview');
        const progressContainer = document.getElementById('progressContainer');
        const submitButton = document.getElementById('submitButton');
        const MAX_FILE_SIZE = 100; // MB (increased from 20MB)
        const ALLOWED_FILE_TYPES = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];
        
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = Math.round((file.size / 1024 / 1024) * 100) / 100; // Convert to MB
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    const fileType = fileExtension;
                    
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
                    
                    if (fileType === 'pdf') {
                        iconClass = 'fa-file-pdf';
                        colorClass = 'text-red-500';
                    } else if (['doc', 'docx'].includes(fileType)) {
                        iconClass = 'fa-file-word';
                        colorClass = 'text-blue-500';
                    } else if (['xls', 'xlsx'].includes(fileType)) {
                        iconClass = 'fa-file-excel';
                        colorClass = 'text-green-500';
                    } else if (['ppt', 'pptx'].includes(fileType)) {
                        iconClass = 'fa-file-powerpoint';
                        colorClass = 'text-orange-500';
                    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                        iconClass = 'fa-file-image';
                        colorClass = 'text-purple-500';
                    } else if (['zip', 'rar'].includes(fileType)) {
                        iconClass = 'fa-file-archive';
                        colorClass = 'text-yellow-600';
                    }
                    
                    // Create preview element
                    const previewEl = document.createElement('div');
                    previewEl.className = 'flex items-center p-3 bg-gray-50 rounded-lg mt-3 border border-gray-200';
                    previewEl.innerHTML = `
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center ${colorClass} bg-gray-100">
                            <i class="fas ${iconClass} text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                            <p class="text-xs text-gray-500">${fileSize} MB</p>
                        </div>
                        <button type="button" class="remove-file ml-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    filePreview.appendChild(previewEl);
                    
                    // Add event listener to remove button
                    const removeButton = previewEl.querySelector('.remove-file');
                    removeButton.addEventListener('click', function() {
                        fileInput.value = '';
                        filePreview.innerHTML = '';
                        progressContainer.classList.add('hidden');
                    });
                    
                    // Enable submit button
                    submitButton.disabled = false;
                }
            });
        }
        
        function showError(message) {
            // Create error element
            const errorEl = document.createElement('div');
            errorEl.className = 'text-red-500 text-sm mt-2';
            errorEl.textContent = message;
            
            // Clear any previous error
            const prevError = fileInput.parentNode.querySelector('.text-red-500');
            if (prevError) prevError.remove();
            
            // Add error after file input
            fileInput.parentNode.appendChild(errorEl);
            
            // Hide progress container
            progressContainer.classList.add('hidden');
        }
    });
</script>
@endpush