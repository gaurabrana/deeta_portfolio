$(document).ready(function () {
    $(document).on('click', '.delete-media-btn', function () {        
        const uploadId = $(this).data('upload-id');
        const formId = $(this).data('form-id');
        const $button = $(this);
        const statusBox = document.getElementById("delete-info-" + uploadId);
        if (!uploadId) return;

        if (!confirm('Are you sure you want to delete this media?')) return;

        $.ajax({
            url: './database/delete_upload.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ upload_id: uploadId }),
            success: function (response) {
                if (response.success) {
                    // remove container
                    $('#media-preview-container-' + uploadId).remove();
                    $('#' + formId).remove();
                    if (response.file_deleted) {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-success">Media record and file deleted successfully.</div>`,
                            { duration: 3000 }
                        );
                    } else if (response.file_delete_error) {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-warning">Record deleted, but file could not be removed from server.</div>`,
                            { duration: 3000 }
                        );
                    } else if (response.file_missing) {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-info">Record deleted. File was already missing.</div>`,
                            { duration: 3000 }
                        );
                    } else {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-success">Record deleted (unknown file state).</div>`,
                            { duration: 3000 }
                        );
                    }
                } else {
                    showTemporaryStatus(
                        statusBox,
                        '<div class="alert alert-danger">Failed to delete media: ' + (response.error || 'Unknown error') + '</div>',
                        { duration: 3000 }
                    );
                }
            },
            error: function (xhr, status, error) {
                showTemporaryStatus(
                    statusBox,
                    '<div class="alert alert-danger"> Error deleting media: ' + error + '</div > ',
                    { duration: 3000 }
                );
            }
        });
    });


    document.addEventListener('change', function (e) {
        const input = e.target;

        // Only proceed if the changed element has the class 'media-input'
        if (!input.classList.contains('media-input')) return;

        const file = input.files[0];
        const previewId = input.dataset.preview;
        const previewContainer = document.getElementById(previewId);
        if (!previewContainer) return;

        previewContainer.innerHTML = '';

        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const type = file.type;
            if (type.startsWith('image/')) {
                previewContainer.innerHTML = `
                <img src="${e.target.result}" style="max-width:150px; max-height:150px; border:1px solid #ccc; border-radius:6px;">
            `;
            } else if (type.startsWith('video/')) {
                previewContainer.innerHTML = `
                <video src="${e.target.result}" controls style="max-width:300px; max-height:300px; border:1px solid #ccc; border-radius:6px;"></video>
            `;
            } else {
                previewContainer.innerHTML = `<div class="text-danger">Unsupported file type.</div>`;
            }
        };

        reader.readAsDataURL(file);
    });


    document.body.addEventListener('submit', function (e) {
        if (e.target && e.target.matches('.media-upload-form')) {
            e.preventDefault();

            const form = e.target; // Correct form reference
            const formId = form.id;
            const fileInput = form.querySelector('input[type="file"]');
            const previewId = fileInput.dataset.preview;
            const statusId = fileInput.dataset.status;
            const statusBox = document.getElementById(statusId);
            const deleteButton = form.querySelector('.delete-media-btn'); // Single button

            const formData = new FormData(form);

            fetch('./database/upload.php', {
                method: 'POST',
                body: formData
            })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {                    
                    if (data.success) {
                        // Successful upload                        
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-success">${data.message}</div>`,
                            { duration: 3000 }
                        );

                        // Make button visible if hidden
                        if (deleteButton && !deleteButton.classList.contains('visible-delete-button')) {
                            deleteButton.classList.add('visible-delete-button');
                            deleteButton.classList.remove('hidden-delete-button');
                        }

                        if (data.new_upload) {
                            // clear form
                            clearEditableFields(formId);
                        }
                        else {
                            // clear existin data file field only
                            // for given form id, clear input file only
                            // because position and text value are already in ui
                            clearMediaFileInput(formId);
                        }

                        // Handle the media display based on scenario
                        if (data.file_deleted) {
                            console.log('Previous file was successfully deleted');
                        }

                        if (data.file_delete_error) {
                            console.warn('Previous file could not be deleted:', data.file_delete_error);
                        }

                        if (data.new_upload) {
                            // Clear any previous preview
                            document.getElementById(previewId).innerHTML = '';
                            $('#section-media-container-' + data.section_id).append(data.content_html);
                            $('.manage-content-uploads-container-' + data.section_id).append(data.manage_content_html);
                        }
                        else {
                            // Update the media display
                            updateMediaSection(data);
                        }


                    } else {
                        // Failed upload
                        let errorMessage = data.message || 'Upload failed';
                        if (data.file_exists_but_not_deleted) {
                            errorMessage += ' (could not remove previous file)';
                        }
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-danger">${errorMessage}</div>`,
                            { duration: 3000 }
                        );
                    }
                })
                .catch(err => {
                    const statusBox = document.getElementById('upload-status');
                    console.error('Upload error:', err);
                    showTemporaryStatus(
                        statusBox,
                        `
        <div class="alert alert-danger">
            Upload failed: ${err.message || 'Network or server error'}
        </div>
    `,
                        { duration: 3000 }
                    );


                    // Optional: Show more detailed error in console for debugging
                    if (err.response) {
                        err.response.text().then(text => console.error('Server response:', text));
                    }
                });
        }
    });

    // Handle image gallery form submission    
    async function resizeImage(file, maxWidth = 1280, maxHeight = 1280) {
        return new Promise((resolve) => {
            const img = new Image();
            const canvas = document.createElement('canvas');
            const reader = new FileReader();

            reader.onload = (e) => {
                img.onload = () => {
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth || height > maxHeight) {
                        const aspect = width / height;
                        if (width > height) {
                            width = maxWidth;
                            height = Math.round(width / aspect);
                        } else {
                            height = maxHeight;
                            width = Math.round(height * aspect);
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        const resizedFile = new File([blob], file.name, { type: file.type });
                        resolve(resizedFile);
                    }, file.type, 0.9); // 0.9 is quality
                };
                img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        });
    }

    $('#resumeUploadForm').on('submit', async function (e) {
        e.preventDefault();

        const fileInput = this.querySelector('input[type="file"]');
        const previewId = fileInput.dataset.preview;
        const statusId = fileInput.dataset.status;
        const statusBox = document.getElementById(statusId);

        const formData = new FormData(this);

        fetch('./database/upload.php', {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Successful upload                        
                    showTemporaryStatus(
                        statusBox,
                        `<div class="alert alert-success">${data.message}</div>`,
                        { duration: 3000 }
                    );
                    setTimeout(() => location.reload(), 3000);

                    // Clear any previous preview
                    document.getElementById(previewId).innerHTML = '';
                } else {
                    // Failed upload
                    let errorMessage = data.message || 'Upload failed';
                    if (data.file_exists_but_not_deleted) {
                        errorMessage += ' (could not remove previous file)';
                    }
                    showTemporaryStatus(
                        statusBox,
                        `<div class="alert alert-danger">${errorMessage}</div>`,
                        { duration: 3000 }
                    );
                }
            })
            .catch(err => {
                const statusBox = document.getElementById('upload-status');
                console.error('Upload error:', err);
                showTemporaryStatus(
                    statusBox,
                    `
        <div class="alert alert-danger">
            Upload failed: ${err.message || 'Network or server error'}
        </div>
    `,
                    { duration: 3000 }
                );


                // Optional: Show more detailed error in console for debugging
                if (err.response) {
                    err.response.text().then(text => console.error('Server response:', text));
                }
            });
    });

    $('.delete-resume-btn').on('click', function () {
        const id = $(this).attr('id'); // FIXED: use attr to get the ID
        const uploadId = id && id.includes('remove-upload-resume-')
            ? id.split('remove-upload-resume-')[1]
            : null;
        const statusBox = document.getElementById("resume-upload-status");
        if (!uploadId) return;

        if (!confirm('Are you sure you want to delete this media?')) return;

        $.ajax({
            url: './database/delete_upload.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ upload_id: uploadId }),
            success: function (response) {
                // Ensure JSON object
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        console.error('Invalid JSON:', e);
                        showTemporaryStatus(
                            statusBox,
                            '<div class="alert alert-danger">Error deleting media</div>',
                            { duration: 3000 }
                        );
                        return;
                    }
                }

                if (response.success) {
                    // Remove preview container
                    $('#pdf-preview-container').remove();
                    $('#resume-download-button').hide();
                    $('.delete-resume-btn').hide();

                    if (response.file_deleted) {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-success">Resume deleted. Please wait for page reload.</div>`,
                            { duration: 3000 }
                        );
                    } else if (response.file_delete_error) {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-warning">Record deleted, but file could not be removed from server.</div>`,
                            { duration: 3000 }
                        );
                    } else if (response.file_missing) {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-info">Record deleted. File was already missing.</div>`,
                            { duration: 3000 }
                        );
                    } else {
                        showTemporaryStatus(
                            statusBox,
                            `<div class="alert alert-success">Record deleted (unknown file state).</div>`,
                            { duration: 3000 }
                        );
                    }
                    setTimeout(() => location.reload(), 3000);
                } else {
                    showTemporaryStatus(
                        statusBox,
                        '<div class="alert alert-danger">Failed to delete media: ' + (response.error || 'Unknown error') + '</div>',
                        { duration: 3000 }
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                showTemporaryStatus(
                    statusBox,
                    '<div class="alert alert-danger">Error deleting media: ' + error + '</div>',
                    { duration: 3000 }
                );
            }
        });
    });

    $('#imageGalleryForm').on('submit', async function (e) {
        e.preventDefault();

        const originalFormData = new FormData(this);
        const newFormData = new FormData();

        $('#imageResizeProgress').html('Preparing images for upload...').show();

        // Copy all fields except files
        for (let [key, value] of originalFormData.entries()) {
            if (key !== 'imageUpload[]') {
                newFormData.append(key, value);
            }
        }

        // Add action explicitly
        newFormData.append('action', 'upload_image');

        // Handle image files (resize if > 5MB)
        const files = $('input[name="imageUpload[]"]')[0].files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            if (file.size > 5 * 1024 * 1024) {
                $('#imageResizeProgress').html(`Resizing image ${i + 1} of ${files.length}...`);
                const resized = await resizeImage(file);
                newFormData.append('imageUpload[]', resized, resized.name);
            } else {
                newFormData.append('imageUpload[]', file, file.name);
            }
        }

        $('#imageResizeProgress').html('Uploading images...');

        $.ajax({
            url: './database/gallery_upload.php',
            type: 'POST',
            data: newFormData,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#imageResizeProgress').hide(); // Hide after upload
                try {
                    if (data.success) {
                        $('#imageGalleryFormResponse').html(
                            '<div class="alert alert-success">Upload Success.</div>'
                        );
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        $('#imageGalleryFormResponse').html(
                            '<div class="alert alert-danger">' + data.errors[0] + '</div>'
                        );
                    }
                } catch (e) {
                    $('#imageGalleryFormResponse').html(
                        '<div class="alert alert-danger">Error processing response</div>'
                    );
                }
            },
            error: function () {
                $('#imageGalleryFormResponse').html(
                    '<div class="alert alert-danger">Error submitting form</div>'
                );
            }
        });
    });



    // Handle video gallery form submission
    $('#videoGalleryForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'upload_video');

        // Remove unused field based on selection
        var videoSource = $('input[name="videoSource"]:checked').val();
        if (videoSource === 'youtube') {
            if (!$('#youtubeId').val()) {
                $('#youtubeError').text('Please validate the YouTube URL first').show();
                return false;
            }
            formData.delete('videoUpload');
        } else {
            formData.delete('videoUrl');
        }

        $.ajax({
            url: './database/gallery_upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                try {
                    if (data.success) {
                        $('#videoGalleryFormResponse').html(
                            '<div class="alert alert-success">Upload successful!</div>'
                        );

                        // Reset form and UI
                        $('#videoGalleryForm')[0].reset();
                        $('#youtubePreview').hide();
                        $('#youtubeError').hide();
                        $('#youtubeSourceContainer').show();
                        $('#uploadSourceContainer').hide();

                        // Reload after 3 seconds
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    } else {
                        $('#videoGalleryFormResponse').html(
                            '<div class="alert alert-danger">' + (data.message || 'Upload failed') + '</div>'
                        );
                    }
                } catch (e) {
                    $('#videoGalleryFormResponse').html(
                        '<div class="alert alert-danger">Invalid server response</div>'
                    );
                }
            },
            error: function (xhr) {
                const errorText = xhr.responseText;

                if (errorText.includes('exceeds the limit')) {
                    $('#videoGalleryFormResponse').html(
                        '<div class="alert alert-danger">Upload failed: File size exceeds server limit (40MB).</div>'
                    );
                } else {
                    $('#videoGalleryFormResponse').html(
                        '<div class="alert alert-danger">Error submitting form</div>'
                    );
                }
            }

        });
    });

    // For videos
    handleDelete('.video-delete-overlay');

    // For images (if same API used or different endpoint)
    handleDelete('.delete-overlay');

    // preview container generate for gallery image and videos
    const types = ['image', 'video'];
    types.forEach(function (type) {
        const formId = (type === 'video') ? 'videoGalleryForm' : 'imageGalleryForm';
        const $input = $('#' + formId + ' input[type="file"]');
        const $preview = $('#' + formId + 'Preview');

        if ($input.length && $preview.length) {
            $input.on('change', function (e) {
                $preview.empty(); // Clear previous previews
                const files = e.target.files;
                let errorMessages = [];
                const maxSizeMB = 40;
                const maxFiles = 10;

                // ✅ Only check file count if type is image
                if (type === 'image' && files.length > maxFiles) {
                    errorMessages.push(`You can upload a maximum of ${maxFiles} images.`);
                }

                $.each(files, function (i, file) {
                    // ✅ Skip remaining files if type is image and over limit
                    if (type === 'image' && i >= maxFiles) return false;

                    const fileSizeMB = file.size / (1024 * 1024);
                    if (fileSizeMB > maxSizeMB) {
                        errorMessages.push(`${file.name} exceeds ${maxSizeMB}MB`);
                        return; // Skip preview for this file
                    }

                    const url = URL.createObjectURL(file);
                    let $media;

                    if (type === 'image') {
                        $media = $('<img>').attr('src', url).attr('alt', file.name);
                    } else {
                        $media = $('<video>').attr('src', url).attr('controls', true);
                    }

                    $media.css({
                        'max-width': '200px',
                        'max-height': '150px',
                        'margin': '5px',
                        'border': '1px solid #ccc',
                        'border-radius': '6px',
                        'object-fit': 'cover'
                    });

                    $preview.append($media);
                });

                // Show error message if any
                const $errorContainer = $('#' + formId + 'Error');
                if (errorMessages.length > 0) {
                    if ($errorContainer.length === 0) {
                        $input.after(`<div id="${formId}Error" class="text-danger mt-2"></div>`);
                    }
                    $('#' + formId + 'Error').html(errorMessages.join('<br>'));
                    $input.val(''); // Clear input
                    $preview.empty(); // Clear previews since input is reset
                } else {
                    $('#' + formId + 'Error').remove(); // Clear previous error if any
                }
            });
        }
    });

});

































































/// FUNCTIONS STARTS FROM HERE
function handleDelete(buttonClass) {
    $(document).on('click', buttonClass + ' button', function (e) {
        const overlay = $(this).parent();
        const id = overlay.data('id');
        const type = overlay.data('type');
        const listItem = overlay.closest('li');

        if (!id || !type) {
            alert('Invalid delete request.');
            return;
        }

        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: "./database/delete_upload.php",
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                    upload_id: id,
                    type: type,
                    delete_type: 'gallery'
                }),
                success: function (response) {
                    if (response.success) {
                        listItem.fadeOut(300, function () {
                            $(this).remove();
                        });
                    } else {
                        let errorMsg = response.error || 'Unknown error';
                        if (response.file_delete_error) {
                            errorMsg += '\nFile deletion failed: ' + response.file_delete_error;
                        }
                        alert('Delete failed: ' + errorMsg);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Request failed: ' + error);
                }
            });
        }
    });
}



// Function to clear all fields with class 'editable-field' within a specific form
function clearEditableFields(formId) {
    // Get the form element
    const form = document.getElementById(formId);

    if (form) {
        // Find all elements with class 'editable-field' within the form
        const editableFields = form.getElementsByClassName('editable-field');

        // Convert HTMLCollection to Array for easier iteration
        Array.from(editableFields).forEach(field => {
            // Handle different input types appropriately
            if (field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                // Clear standard input fields
                if (field.type === 'checkbox' || field.type === 'radio') {
                    field.checked = false;
                } else {
                    field.value = '';
                }
            } else if (field.tagName === 'SELECT') {
                // Reset select fields to first option
                field.selectedIndex = 0;
            } else {
                // Clear content for other elements (like contenteditable divs)
                field.textContent = '';
            }
        });

        console.log(`Cleared ${editableFields.length} editable fields in form ${formId}`);
        return true;
    } else {
        console.error(`Form with ID ${formId} not found`);
        return false;
    }
}

/**
 * Clears all file input fields within a specified form
 * @param {string|HTMLElement} form - Form ID or form element
 * @returns {boolean} - Returns true if successful, false if form not found
 */
function clearMediaFileInput(form) {
    const formEl = typeof form === 'string' ? document.getElementById(form) : form;
    if (!formEl) return false;

    const fileInput = formEl.querySelector('input.media-input[type="file"]');
    if (!fileInput) return false;

    // Create new input with all original attributes
    const newInput = document.createElement('input');
    newInput.type = 'file';

    // Copy all attributes
    Array.from(fileInput.attributes).forEach(attr => {
        newInput.setAttribute(attr.name, attr.value);
    });

    // Replace in DOM
    fileInput.replaceWith(newInput);

    // Trigger change event for any listeners
    setTimeout(() => {
        newInput.dispatchEvent(new Event('change'));
    }, 0);

    return true;
}

/**
 * Gets the preview ID from the form's hidden input and clears the target element
 * @param {HTMLFormElement} formId - The form element id
 * @returns {boolean} - Returns true if successful, false if failed
 */
function clearPreviewElement(formId) {
    const form = document.getElementById(formId);
    // 1. Get the preview_id from the hidden input
    const previewIdInput = form.querySelector('input[name="preview_id"]');
    if (!previewIdInput) {
        console.error('Hidden preview_id input not found in form');
        return false;
    }

    const previewId = previewIdInput.value;
    if (!previewId) {
        console.error('Preview ID value is empty');
        return false;
    }

    // 2. Find the target element to clear
    const previewElement = document.getElementById(previewId);
    if (!previewElement) {
        console.error(`Preview element with ID "${previewId}" not found`);
        return false;
    }

    // 3. Clear the element
    previewElement.innerHTML = '';
    console.log(`Cleared preview element: ${previewId}`);
    return true;
}

/**
 * Shows a temporary status message in an element and clears it after delay
 * @param {HTMLElement} element - The element to display the message in
 * @param {string} message - The message to display
 * @param {object} options - Configuration options
 * @param {number} [options.duration=3000] - How long to show the message (ms)
 * @param {string} [options.baseClass='status-message'] - Base CSS class
 * @param {boolean} [options.keepSpace=false] - Whether to keep element height when empty
 */
function showTemporaryStatus(element, message, options = {}) {
    // Default options
    const {
        duration = 3000,
        baseClass = 'status-message',
        keepSpace = false
    } = options;

    // Clear any existing timeout to prevent premature clearing
    if (element._statusTimeout) {
        clearTimeout(element._statusTimeout);
    }

    // Set the message
    element.innerHTML = message;

    // Add base class if not present
    if (!element.classList.contains(baseClass)) {
        element.classList.add(baseClass);
    }

    // Set timeout to clear
    element._statusTimeout = setTimeout(() => {
        if (keepSpace) {
            element.innerHTML = '&nbsp;'; // Maintain layout
        } else {
            element.innerHTML = '';
        }
        element._statusTimeout = null;
    }, duration);
}

function updateMediaSection(data) {
    const sectionEl = document.getElementById('media-preview-container-' + data.upload_id);

    if (!sectionEl) {
        console.warn(`Section upload with id media-preview-container-${data.upload_id} not found.`);
        return;
    }

    if (sectionEl && sectionEl.classList.contains('hide-empty-asset')) {
        sectionEl.classList.remove('hide-empty-asset');
    }

    const headingEl = document.getElementById('heading-container-' + data.upload_id)

    if (headingEl) {
        headingEl.remove();
    }

    sectionEl.outerHTML = data.content_html;
}
