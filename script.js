


document.getElementById('toggleSidebarMobile').addEventListener('click', function () {
    const mobileMenu = document.getElementById('mobileMenu');
    const isExpanded = this.getAttribute('aria-expanded') === 'true';

    this.setAttribute('aria-expanded', !isExpanded);
    mobileMenu.classList.toggle('hidden');
});

// Export 

