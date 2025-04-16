<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body">
                <i class='bx bx-check-circle me-2'></i>
                <span id="toastMessage">Data berhasil disimpan!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body">
                <i class='bx bx-error-circle me-2'></i>
                <span id="errorToastMessage">Terjadi kesalahan!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <div id="infoToast" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body">
                <i class='bx bx-info-circle me-2'></i>
                <span id="infoToastMessage">Informasi!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    function showToast(message, type = 'success') {
        let toastElement, messageElement;
        
        switch(type) {
            case 'error':
                toastElement = document.getElementById('errorToast');
                messageElement = document.getElementById('errorToastMessage');
                break;
            case 'info':
                toastElement = document.getElementById('infoToast');
                messageElement = document.getElementById('infoToastMessage');
                break;
            case 'success': 
            default:
                toastElement = document.getElementById('successToast');
                messageElement = document.getElementById('toastMessage');
                break;
        }
        
        if (toastElement && messageElement) {
            messageElement.textContent = message || 'Notification';
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Save to sessionStorage for persistent feedback
            sessionStorage.setItem('lastToast', JSON.stringify({
                message: message,
                type: type,
                time: new Date().getTime()
            }));
            
            // Log for debugging
            console.log('Toast shown:', message);
        }
    }

    // Check for flash messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Debug info
        console.log('Session data available:', {
            success: @json(session('success')),
            error: @json(session('error')),
            info: @json(session('info'))
        });
        
        // Check server-side session messages
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        
        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
        
        // Check for redirected form submissions
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('status') && urlParams.has('message')) {
            const status = urlParams.get('status');
            const message = urlParams.get('message');
            if (status && message) {
                showToast(decodeURIComponent(message), status);
            }
        }
        
        // Check if there was a recent toast from another page
        const lastToast = sessionStorage.getItem('lastToast');
        if (lastToast) {
            try {
                const toast = JSON.parse(lastToast);
                const now = new Date().getTime();
                
                // Only show if it's less than 2 seconds old (to prevent repeated toasts)
                if (now - toast.time < 2000) {
                    showToast(toast.message, toast.type);
                }
                
                // Clear the stored toast
                sessionStorage.removeItem('lastToast');
            } catch (e) {
                console.error('Error parsing last toast', e);
            }
        }
        
        // Force check for URL parameters even without the standard parameters
        const urlParams = new URLSearchParams(window.location.search);
        console.log('URL parameters:', Object.fromEntries(urlParams.entries()));
    });
    
    // Add a function to show visual highlight on specific element
    function highlightElement(selector, duration = 3000) {
        const element = document.querySelector(selector);
        if (element) {
            element.classList.add('highlight-element');
            setTimeout(() => {
                element.classList.remove('highlight-element');
            }, duration);
        }
    }
</script>

<style>
    .highlight-element {
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 0, 102, 179), 0.5);
        animation: pulse-highlight 2s infinite;
    }
    
    @keyframes pulse-highlight {
        0% {
            box-shadow: 0 0 0 0 rgba(var(--primary-rgb, 0, 102, 179), 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(var(--primary-rgb, 0, 102, 179), 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(var(--primary-rgb, 0, 102, 179), 0);
        }
    }
</style>
