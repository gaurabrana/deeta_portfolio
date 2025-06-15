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
            if (!navItem) return;

            navItem.addEventListener('click', function (e) {
                if (window.innerWidth < 768) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu?.classList.toggle('show');
                }
            });

            // Only apply submenu handling if there are nested dropdowns
            const dropdownRoot = document.querySelector(`.${navItemClass}`);
            if (!dropdownRoot) return;

            const submenuToggles = dropdownRoot.querySelectorAll('.dropdown-submenu > .dropdown-toggle');

            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    if (window.innerWidth < 768) {
                        e.preventDefault();
                        const subMenu = this.nextElementSibling;
                        subMenu?.classList.toggle('show');
                        e.stopPropagation(); // Prevent outer dropdown from closing
                    }
                });
            });
        }

        // Apply to all relevant menu items
        handleDropdown('school-nav-item');
        handleDropdown('about-nav-item');  // contains the nested 'Who am I' submenu
        handleDropdown('gallery-nav-item');
        handleDropdown('sports-nav-item');
        handleDropdown('scouting-nav-item');
        handleDropdown('givingback-nav-item');

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.school-nav-item') && !e.target.closest('.about-nav-item') && !e.target.closest('.gallery-nav-item') && !e.target.closest('.sports-nav-item') && !e.target.closest('.scouting-nav-item') && !e.target.closest('.givingback-nav-item')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });

});
