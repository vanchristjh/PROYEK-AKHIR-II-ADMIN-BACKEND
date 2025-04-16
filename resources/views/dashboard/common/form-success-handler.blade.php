<script>
/**
 * Form Success Handler
 * 
 * This script helps manage form submission and success state across the application.
 * It provides visual feedback when a form is submitted and when changes are saved.
 */

class FormSuccessHandler {
    constructor(formId, options = {}) {
        this.form = document.getElementById(formId);
        this.saveButton = options.saveButtonId ? document.getElementById(options.saveButtonId) : null;
        this.successCallback = options.onSuccess || null;
        this.fieldSelectors = options.fields || 'input, select, textarea';
        this.changedFields = [];
        this.formData = {};
        
        // Only initialize if the form exists
        if (this.form) {
            this.init();
        }
    }
    
    init() {
        // Store initial form values when page loads
        this.storeInitialValues();
        
        // Track field changes
        this.trackFieldChanges();
        
        // Handle form submission
        this.handleFormSubmission();
        
        // Check for success message on page load
        this.checkForSuccess();
    }
    
    storeInitialValues() {
        const fields = this.form.querySelectorAll(this.fieldSelectors);
        fields.forEach(field => {
            if (field.name) {
                this.formData[field.name] = this.getFieldValue(field);
            }
        });
    }
    
    getFieldValue(field) {
        if (field.type === 'checkbox') {
            return field.checked;
        } else if (field.type === 'radio') {
            if (field.checked) {
                return field.value;
            }
            return this.formData[field.name]; // Keep existing value if not checked
        } else {
            return field.value;
        }
    }
    
    trackFieldChanges() {
        const fields = this.form.querySelectorAll(this.fieldSelectors);
        fields.forEach(field => {
            if (field.name) {
                field.addEventListener('change', () => {
                    const newValue = this.getFieldValue(field);
                    
                    // For radio buttons, we need special handling
                    if (field.type === 'radio' && !field.checked) {
                        return; // Skip radio buttons that aren't checked
                    }
                    
                    // Compare with original value
                    if (this.formData[field.name] !== newValue) {
                        this.markAsChanged(field);
                        this.changedFields[field.name] = true;
                    }
                });
            }
        });
    }
    
    markAsChanged(field) {
        // Find the appropriate container to highlight
        const container = field.closest('.form-group') || 
                         field.closest('.mb-3') || 
                         field.closest('.form-check') ||
                         field;
                         
        if (container) {
            container.classList.add('field-changed');
            
            // Add visual indicator near label
            const labelFor = field.id;
            if (labelFor) {
                const label = document.querySelector(`label[for="${labelFor}"]`);
                if (label && !label.querySelector('.change-indicator')) {
                    const indicator = document.createElement('span');
                    indicator.className = 'change-indicator ms-2';
                    indicator.innerHTML = '<i class="bx bx-pencil text-primary"></i>';
                    label.appendChild(indicator);
                }
            }
        }
    }
    
    handleFormSubmission() {
        this.form.addEventListener('submit', () => {
            // Store the changes in sessionStorage to access after page reload
            sessionStorage.setItem(
                `${this.form.id}_changedFields`, 
                JSON.stringify(this.changedFields)
            );
            
            // Update button UI if provided
            if (this.saveButton) {
                this.saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                this.saveButton.disabled = true;
            }
        });
    }
    
    checkForSuccess() {
        // Check if we have a success toast or message
        if (typeof showToast === 'function' && window.PAGE_HAS_SUCCESS_MESSAGE) {
            const changedFieldsData = sessionStorage.getItem(`${this.form.id}_changedFields`);
            
            if (changedFieldsData) {
                try {
                    const changedFields = JSON.parse(changedFieldsData);
                    
                    // Highlight fields that were changed
                    setTimeout(() => {
                        Object.keys(changedFields).forEach(fieldName => {
                            const field = this.form.querySelector(`[name="${fieldName}"]`);
                            if (field) {
                                this.highlightSavedField(field);
                            }
                        });
                    }, 500);
                    
                    // Clear the stored changes
                    sessionStorage.removeItem(`${this.form.id}_changedFields`);
                    
                    // Call success callback if provided
                    if (this.successCallback) {
                        this.successCallback();
                    }
                } catch (e) {
                    console.error('Error processing changed fields', e);
                }
            }
        }
    }
    
    highlightSavedField(field) {
        const container = field.closest('.form-group') || 
                         field.closest('.mb-3') || 
                         field.closest('.form-check') ||
                         field;
                         
        if (container) {
            container.classList.add('field-saved');
            
            // Add success mark
            const successMark = document.createElement('div');
            successMark.className = 'save-success-indicator';
            successMark.innerHTML = '<i class="bx bx-check"></i>';
            container.style.position = 'relative';
            container.appendChild(successMark);
            
            // Remove after animation completes
            setTimeout(() => {
                container.classList.remove('field-saved');
                if (successMark.parentNode) {
                    successMark.parentNode.removeChild(successMark);
                }
            }, 3000);
        }
    }
}

// Initialize for success message indicator
window.PAGE_HAS_SUCCESS_MESSAGE = {{ session('success') ? 'true' => 'false' }};

// Add necessary styles
document.head.insertAdjacentHTML('beforeend', `
<style>
    .field-changed {
        border-left: 3px solid var(--primary);
        padding-left: 10px;
        transition: all 0.3s;
    }
    
    .field-saved {
        background-color: rgba(40, 167, 69, 0.05);
        transition: background-color 0.5s;
    }
    
    .save-success-indicator {
        position: absolute;
        right: 0;
        top: 0;
        background: var(--success);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInOut 3s ease-in-out forwards;
        z-index: 5;
    }
    
    @keyframes fadeInOut {
        0% { opacity: 0; transform: scale(0); }
        25% { opacity: 1; transform: scale(1); }
        75% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0); }
    }
</style>
`);
</script>
