/**
 * Enhanced Mobile Menu Toggle
 * Improved mobile navigation functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
        // Improve toggle functionality
        navToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle active class
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
            
            // Accessibility
            const expanded = navToggle.getAttribute('aria-expanded') === 'true' || false;
            navToggle.setAttribute('aria-expanded', !expanded);
            
            // Prevent body scrolling when menu is open
            document.body.classList.toggle('menu-open');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (navMenu.classList.contains('active') && !navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.classList.remove('menu-open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Close menu when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.classList.remove('menu-open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Add close button to mobile menu
        const closeButton = document.createElement('button');
        closeButton.className = 'nav-close-button';
        closeButton.innerHTML = '<i class="fas fa-times"></i><span class="sr-only">Close Menu</span>';
        closeButton.setAttribute('aria-label', 'Close menu');
        
        closeButton.addEventListener('click', function() {
            navToggle.classList.remove('active');
            navMenu.classList.remove('active');
            document.body.classList.remove('menu-open');
            navToggle.setAttribute('aria-expanded', 'false');
        });
        
        // Only add close button if it doesn't already exist
        if (!navMenu.querySelector('.nav-close-button')) {
            navMenu.prepend(closeButton);
        }
        
        // Add accessibility attributes
        navToggle.setAttribute('aria-expanded', 'false');
        navToggle.setAttribute('aria-controls', 'nav-menu');
    }
});
