<div id="toast-notification" class="fixed bottom-4 right-4 z-50 hidden">
    <div class="flex items-center p-4 mb-4 rounded-lg shadow min-w-64" role="alert">
        <div id="toast-icon" class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg"></div>
        <div class="ml-3 text-sm font-normal" id="toast-message"></div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" 
                onclick="document.getElementById('toast-notification').classList.add('hidden')">
            <span class="sr-only">Close</span>
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');
        
        // Reset classes
        toast.querySelector('div').className = 'flex items-center p-4 mb-4 rounded-lg shadow min-w-64';
        toastIcon.className = 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg';
        
        // Set message
        toastMessage.textContent = message;
        
        // Set toast type (success, error, warning)
        if (type === 'success') {
            toast.querySelector('div').classList.add('bg-green-50', 'text-green-800');
            toastIcon.classList.add('bg-green-100', 'text-green-500');
            toastIcon.innerHTML = '<i class="fas fa-check"></i>';
        } else if (type === 'error') {
            toast.querySelector('div').classList.add('bg-red-50', 'text-red-800');
            toastIcon.classList.add('bg-red-100', 'text-red-500');
            toastIcon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
        } else if (type === 'warning') {
            toast.querySelector('div').classList.add('bg-yellow-50', 'text-yellow-800');
            toastIcon.classList.add('bg-yellow-100', 'text-yellow-500');
            toastIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        } else if (type === 'info') {
            toast.querySelector('div').classList.add('bg-blue-50', 'text-blue-800');
            toastIcon.classList.add('bg-blue-100', 'text-blue-500');
            toastIcon.innerHTML = '<i class="fas fa-info-circle"></i>';
        }
        
        // Show toast
        toast.classList.remove('hidden');
        
        // Add animation
        toast.animate([
            { opacity: 0, transform: 'translateY(20px)' },
            { opacity: 1, transform: 'translateY(0)' }
        ], {
            duration: 300,
            easing: 'ease-out'
        });
        
        // Auto hide toast after 5 seconds
        setTimeout(() => {
            toast.animate([
                { opacity: 1 },
                { opacity: 0 }
            ], {
                duration: 300,
                easing: 'ease-in'
            }).onfinish = () => {
                toast.classList.add('hidden');
            };
        }, 5000);
    }

    // Show flash messages if they exist
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
        
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
    });
</script>
