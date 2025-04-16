document.addEventListener('DOMContentLoaded', function() {
    // Handle the toggle button separately from the main link
    const toggleBtns = document.querySelectorAll('.menu-toggle-btn');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const menuItem = this.closest('.menu-item');
            menuItem.classList.toggle('open');
        });
    });
});
