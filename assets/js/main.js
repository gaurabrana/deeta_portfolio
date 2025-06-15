$(window).on('load', function () {
    $('#preloader').fadeOut('slow'); // Hide the preloader with a fade-out effect    
});

document.addEventListener("DOMContentLoaded", function () {
    AOS.init();
    const currentYear = new Date().getFullYear();
    document.getElementById("current-year").textContent = currentYear;

    document.addEventListener('DOMContentLoaded', function () {
        function handleDropdown(navItemClass) {
            const navItem = document.querySelector(`.${navItemClass} a.nav-link`);
            
            if (!navItem) return; // Guard against missing elements
            
            navItem.addEventListener('click', function (e) {
                if (window.innerWidth < 768) {
                    e.preventDefault(); // Prevent default navigation behavior
    
                    const dropdownMenu = this.nextElementSibling;
                    const isVisible = dropdownMenu.classList.contains('show');
    
                    // Toggle the visibility of the dropdown menu
                    if (!isVisible) {
                        dropdownMenu.classList.add('show');
                    } else {
                        dropdownMenu.classList.remove('show');
                    }
                }
            });
        }
    
        // Apply the behavior to both dropdowns
        handleDropdown('services-nav-item');
        handleDropdown('about-nav-item');
        handleDropdown('gallery-nav-item');
    
        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.services-nav-item') && !e.target.closest('.about-nav-item')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });       
    
});
