/**
 * Toast notification utility for the attendance system
 * This helps show consistent toast notifications across all pages
 */
function showToast(message, type = 'info') {
    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {
        const toastElement = document.getElementById(`${type}Toast`) || document.getElementById('infoToast');
        if (toastElement) {
            const messageElement = document.getElementById(`${type}ToastMessage`) || document.getElementById('infoToastMessage');
            if (messageElement) messageElement.textContent = message;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        } else {
            // Create toast element if it doesn't exist
            createToast(message, type);
        }
    } else {
        console.log(`[${type.toUpperCase()}]: ${message}`);
    }
}

function createToast(message, type) {
    // Define toast container
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Create the toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.role = 'alert';
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.setAttribute('data-bs-delay', '3000');
    
    // Set toast content
    let iconClass = 'bx-info-circle';
    switch (type) {
        case 'success': iconClass = 'bx-check-circle'; break;
        case 'danger': case 'error': iconClass = 'bx-error-circle'; type = 'danger'; break;
        case 'warning': iconClass = 'bx-error'; break;
    }
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class='bx ${iconClass} me-2'></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Initialize and show
    if (typeof bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove from DOM after it's hidden
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
}
