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

    $('.video-source').change(function () {
        if ($(this).val() === 'youtube') {
            $('#youtubeSourceContainer').show();
            $('#uploadSourceContainer').hide();
            $('#videoUrl').prop('required', true);
            $('#videoUpload').prop('required', false);
        } else {
            $('#youtubeSourceContainer').hide();
            $('#uploadSourceContainer').show();
            $('#videoUrl').prop('required', false);
            $('#videoUpload').prop('required', true);
        }
    });

    // YouTube URL validation and preview
    $('#validateYoutubeBtn').click(validateYouTubeUrl);
    $('#videoUrl').on('change paste keyup', function () {
        $('#youtubePreview').hide();
        $('#youtubeError').hide();
    });
});


function validateYouTubeUrl() {
    const url = $('#videoUrl').val().trim();
    const errorDiv = $('#youtubeError');
    const previewDiv = $('#youtubePreview');

    if (!url) {
        errorDiv.text('Please enter a YouTube URL').show();
        return;
    }

    // Reset display
    errorDiv.hide();
    previewDiv.hide();

    // Extract YouTube ID
    let videoId = '';
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    if (match && match[2].length === 11) {
        videoId = match[2];
        $('#youtubeId').val(videoId);

        // Show preview
        $('#youtubeThumbnail').attr('src', 'https://img.youtube.com/vi/' + videoId + '/mqdefault.jpg');
        previewDiv.show();
    } else {
        errorDiv.text('Invalid YouTube URL. Please use a standard YouTube link.').show();
    }
}