document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.getElementById('nav-toggle');
    const navLinks = document.getElementById('nav-links');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            const isVisible = navLinks.style.display === 'flex';
            navLinks.style.display = isVisible ? 'none' : 'flex';
        });
    }
});
