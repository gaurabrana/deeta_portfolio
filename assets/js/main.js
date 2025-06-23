$(window).on('load', function () {
    $('#preloader').fadeOut('slow'); // Hide the preloader with a fade-out effect    
});

document.addEventListener("DOMContentLoaded", function () {
    AOS.init();

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

    $('#resumeFile').on('change', function (event) {
        const file = event.target.files[0];
        const $previewContainer = $('#resume-media-preview');

        $previewContainer.empty(); // Clear previous preview

        if (file && file.type === 'application/pdf') {
            const fileURL = URL.createObjectURL(file);
            const embed = $('<embed>', {
                src: fileURL,
                type: 'application/pdf',
                width: '100%',
                height: '400px',
                css: {
                    border: '1px solid #ccc'
                }
            });

            $previewContainer.append(embed);
        } else {
            $previewContainer.html('<div class="text-danger">Please select a valid PDF file.</div>');
        }
    });


    function renderPdfPreview(pdfUrl) {
        // Clear previous preview if any
        // Show loading indicator
        loadingIndicator.style.display = 'block';
        previewContainer.innerHTML = ''; // Clear previous preview

        pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const scale = 2;
                const viewport = page.getViewport({ scale });

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                page.render({ canvasContext: context, viewport }).promise.then(() => {
                    const img = document.createElement('img');
                    img.src = canvas.toDataURL(); // Convert canvas to image
                    img.alt = 'PDF Preview';
                    img.classList.add('img-fluid');
                    previewContainer.appendChild(img);
                    loadingIndicator.style.display = 'none';
                    downloadButton.style.display = 'block';
                });
            });
        }).catch(err => {
            loadingIndicator.style.display = 'none';
            container.innerHTML = '<p class="text-danger">Failed to load PDF preview.</p>';
            console.error(err);
        });
    }

    // Example: get the pdf path from the hidden input and render preview
    const pdfPathField = document.getElementById('pdfPath');
    const loadingIndicator = document.getElementById('pdf-loading');
    const previewContainer = document.getElementById('pdf-preview-container');
    const downloadButton = document.getElementById('resume-download-button');
    if (pdfPathField) {
        const pdfPath = pdfPathField.value;
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        renderPdfPreview(pdfPath);
    }


    $(document).ready(function () {
        $('#login-form').on('submit', function (e) {
            e.preventDefault(); // Prevent actual form submission

            const $form = $(this);
            const $status = $('#loginStatus');

            $status
                .removeClass('text-success text-danger')
                .text('Logging in...')
                .show();

            $.ajax({
                url: "./database/admin_login.php",
                type: 'POST',
                data: $form.serialize(),
                success: function (response) {
                    let res;

                    // Try to parse JSON safely
                    try {
                        res = (typeof response === 'object') ? response : JSON.parse(response);
                    } catch (e) {
                        // Parsing failed, fallback error
                        $status
                            .removeClass('text-success')
                            .addClass('text-danger')
                            .text('Unexpected server response.');
                        return;
                    }

                    if (res.status === 'success') {
                        $status
                            .removeClass('text-danger')
                            .addClass('text-success')
                            .text(res.message + ' Redirecting...');

                        setTimeout(() => {
                            window.location.href = 'about_me.php';
                        }, 1000);
                    } else if (res.status === 'error') {
                        $status
                            .removeClass('text-success')
                            .addClass('text-danger')
                            .text(res.message);
                    } else {
                        $status
                            .removeClass('text-success')
                            .addClass('text-danger')
                            .text('Unknown response status.');
                    }
                },
                error: function (xhr, status, error) {
                    $status
                        .removeClass('text-success')
                        .addClass('text-danger')
                        .text('An error occurred: ' + (error || 'Please try again.'));
                }
            });
        });
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