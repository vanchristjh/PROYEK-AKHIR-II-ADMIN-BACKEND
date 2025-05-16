/**
 * User Management JavaScript Utilities
 */

// Document Ready Function
document.addEventListener('DOMContentLoaded', function() {
    
    // Handle Delete User Confirmation
    initDeleteConfirmation();
    
    // Initialize Form Validation
    initFormValidation();
    
    // Initialize File Upload Preview
    initFileUploadPreview();
    
    // Initialize Role-based Field Display
    if(document.getElementById('role_id')) {
        initRoleBasedFields();
    }
    
    // Initialize username generator
    initUsernameGenerator();
});

/**
 * Initialize Delete Confirmation Modal
 */
function initDeleteConfirmation() {
    const deleteModal = document.getElementById('deleteModal');
    if(!deleteModal) return;
    
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    const deleteText = document.getElementById('deleteConfirmationText');
    let currentForm = null;

    // Open modal when delete button is clicked
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            currentForm = this.closest('.delete-form');
            
            deleteText.textContent = `Apakah Anda yakin ingin menghapus pengguna "${userName}"?`;
            deleteModal.classList.remove('hidden');
            
            // Add animation
            const modalContent = deleteModal.querySelector('.bg-white');
            modalContent.animate([
                { opacity: 0, transform: 'scale(0.9)' },
                { opacity: 1, transform: 'scale(1)' }
            ], {
                duration: 200,
                easing: 'ease-out'
            });
        });
    });

    // Close modal when cancel is clicked
    if(cancelDelete) {
        cancelDelete.addEventListener('click', function() {
            closeDeleteModal();
        });
    }

    // Close modal when clicking outside
    if(deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }

    // Submit form when confirm is clicked
    if(confirmDelete) {
        confirmDelete.addEventListener('click', function() {
            if (currentForm) {
                // Show loading state
                confirmDelete.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghapus...';
                confirmDelete.disabled = true;
                
                // Submit the form
                currentForm.submit();
            }
        });
    }
    
    // Function to close modal with animation
    function closeDeleteModal() {
        if(!deleteModal) return;
        
        const modalContent = deleteModal.querySelector('.bg-white');
        modalContent.animate([
            { opacity: 1, transform: 'scale(1)' },
            { opacity: 0, transform: 'scale(0.9)' }
        ], {
            duration: 200,
            easing: 'ease-in'
        }).onfinish = () => {
            deleteModal.classList.add('hidden');
            currentForm = null;
        };
    }
}

/**
 * Initialize Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        
        if(submitBtn) {
            form.addEventListener('submit', function(event) {
                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                        
                        // Add error message if not exists
                        let errorMsg = field.nextElementSibling;
                        if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                            errorMsg = document.createElement('p');
                            errorMsg.classList.add('error-message', 'text-xs', 'text-red-500', 'mt-1');
                            errorMsg.textContent = 'Bidang ini wajib diisi';
                            field.parentNode.insertBefore(errorMsg, field.nextSibling);
                        }
                    }
                });
                
                // Validate password confirmation
                const passwordField = form.querySelector('input[name="password"]');
                const confirmField = form.querySelector('input[name="password_confirmation"]');
                
                if (passwordField && confirmField && 
                    passwordField.value && confirmField.value && 
                    passwordField.value !== confirmField.value) {
                    
                    isValid = false;
                    confirmField.classList.add('border-red-500');
                    
                    // Add mismatch message
                    let errorMsg = confirmField.nextElementSibling;
                    if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                        errorMsg = document.createElement('p');
                        errorMsg.classList.add('error-message', 'text-xs', 'text-red-500', 'mt-1');
                        errorMsg.textContent = 'Password tidak cocok';
                        confirmField.parentNode.insertBefore(errorMsg, confirmField.nextSibling);
                    }
                }
                
                if (isValid) {
                    // Show loading state
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    submitBtn.disabled = true;
                } else {
                    event.preventDefault();
                }
            });
        }
        
        // Remove validation errors when typing
        form.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                
                // Remove error message if exists
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.remove();
                }
            });
        });
    });
}

/**
 * Initialize File Upload Preview
 */
function initFileUploadPreview() {
    const fileInput = document.getElementById('avatar');
    if(!fileInput) return;
    
    fileInput.addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            document.querySelector('.file-name').textContent = fileName;
            
            // Preview image if it's an image file
            const fileReader = new FileReader();
            fileReader.onload = function(e) {
                const previewContainer = document.querySelector('.avatar-preview');
                if(previewContainer) {
                    previewContainer.innerHTML = `
                        <div class="h-20 w-20 rounded-full overflow-hidden border-4 border-white shadow-md">
                            <img src="${e.target.result}" alt="Preview" class="h-full w-full object-cover">
                        </div>
                        <p class="text-xs text-green-600 mt-1">Pratinjau avatar baru</p>
                    `;
                }
            };
            fileReader.readAsDataURL(e.target.files[0]);
        }
    });
}

/**
 * Initialize Role-based Field Display
 */
function initRoleBasedFields() {
    const roleSelect = document.getElementById('role_id');
    const studentFields = document.getElementById('student_fields');
    const teacherFields = document.getElementById('teacher_fields');
    const idNumberLabel = document.getElementById('id_number_label');
    
    if(!roleSelect || !idNumberLabel) return;
    
    // Initial check
    updateFieldsVisibility(roleSelect.value);
    
    // Handle role change
    roleSelect.addEventListener('change', function() {
        updateFieldsVisibility(this.value);
    });
    
    function updateFieldsVisibility(roleId) {
        // Hide all role-specific fields
        if(studentFields) studentFields.style.display = 'none';
        if(teacherFields) teacherFields.style.display = 'none';
        
        // Show fields based on selected role
        // Assuming role_id: 1 = admin, 2 = guru, 3 = siswa
        if (roleId == '3') { // Siswa
            if(studentFields) studentFields.style.display = 'block';
            idNumberLabel.textContent = 'NIS';
        } else if (roleId == '2') { // Guru
            if(teacherFields) teacherFields.style.display = 'block';
            idNumberLabel.textContent = 'NIP';
        } else if (roleId == '1') { // Admin
            idNumberLabel.textContent = 'ID Pengguna';
        } else {
            idNumberLabel.textContent = 'NIP/NIS';
        }
    }
}

/**
 * Initialize Username Generator
 */
function initUsernameGenerator() {
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    
    if(!nameInput || !usernameInput) return;
    
    // Generate username suggestion from name
    nameInput.addEventListener('blur', function() {
        if (!usernameInput.value) {
            // Create a username from the name: lowercase, no spaces, no special chars
            let suggestedUsername = this.value.toLowerCase()
                .replace(/\s+/g, '')
                .replace(/[^a-z0-9]/g, '');
            
            usernameInput.value = suggestedUsername;
        }
    });
}
