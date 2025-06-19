$(document).ready(function () {
    $('.delete-media-btn').on('click', function () {
        const sectionId = $(this).data('upload-id');
        const $button = $(this);
        const statusBox = document.getElementById("delete-info-" + sectionId);
        if (!sectionId) return;

        if (!confirm('Are you sure you want to delete this media?')) return;

        $.ajax({
            url: './database/delete_upload.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ section_id: sectionId }),
            success: function (response) {
                console.log(response); // already a JS object
                if (response.success) {
                    const sectionEl = document.getElementById('media-preview-container-' + sectionId); // ensure this ID exists
                    const sectionContainerForm = document.getElementById('upload-form-container-' + sectionId); // ensure this ID exists
                    // clear file input preview
                    clearPreviewElement(sectionContainerForm.value);
                    // clear section preview
                    if (sectionEl) {
                        sectionEl.innerHTML = '';
                    }
                    //
                    // Hide button
                    $button.removeClass('visible-delete-button')
                        .addClass('hidden-delete-button');
                    // clear fields
                    clearEditableFields(sectionContainerForm.value);
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
                console.log(error);
                showTemporaryStatus(
                    statusBox,
                    '<div class="alert alert-danger"> Error deleting media: ' + error + '</div > ',
                    { duration: 3000 }
                );
            }
        });
    });


    document.querySelectorAll('.media-input').forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            const previewId = this.dataset.preview;
            const previewContainer = document.getElementById(previewId);
            previewContainer.innerHTML = '';

            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const type = file.type;
                if (type.startsWith('image/')) {
                    previewContainer.innerHTML = `<img src="${e.target.result}" style="max-width:150px; max-height:150px; border:1px solid #ccc; border-radius:6px;">`;
                } else if (type.startsWith('video/')) {
                    previewContainer.innerHTML = `<video src="${e.target.result}" controls style="max-width:300px; max-height:300px; border:1px solid #ccc; border-radius:6px;"></video>`;
                } else {
                    previewContainer.innerHTML = '<div class="text-danger">Unsupported file type.</div>';
                }
            };
            reader.readAsDataURL(file);
        });
    });

    document.querySelectorAll('.media-upload-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formId = this.id;
            const fileInput = this.querySelector('input[type="file"]');
            const previewId = fileInput.dataset.preview;
            const statusId = fileInput.dataset.status;
            const statusBox = document.getElementById(statusId);
            const deleteButton = this.querySelector('.delete-media-btn'); // Single button

            const formData = new FormData(this);

            if (!fileInput.files.length) {
                showTemporaryStatus(
                    statusBox,
                    '<div class="alert alert-danger">Please select a file.</div>',
                    { duration: 3000 }
                );
                return;
            }

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

                        // Clear any previous preview
                        document.getElementById(previewId).innerHTML = '';

                        // Make button visible if hidden
                        if (deleteButton && !deleteButton.classList.contains('visible-delete-button')) {
                            deleteButton.classList.add('visible-delete-button');
                            deleteButton.classList.remove('hidden-delete-button');
                        }

                        // for given form id, clear input file only
                        // because position and text value are already in ui
                        clearMediaFileInput(formId);

                        // Handle the media display based on scenario
                        if (data.file_deleted) {
                            console.log('Previous file was successfully deleted');
                        }

                        if (data.file_delete_error) {
                            console.warn('Previous file could not be deleted:', data.file_delete_error);
                        }

                        // Update the media display
                        const sectionEl = document.getElementById('media-preview-container-' + data.section_slug);
                        if (sectionEl) {
                            // Either insert new or replace existing media
                            sectionEl.innerHTML = data.html;

                            // If it's a video, we might need to reload it for proper playback
                            if (data.media_type === 'video') {
                                const videos = sectionEl.getElementsByTagName('video');
                                for (let video of videos) {
                                    video.load();
                                }
                            }
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
        });
    });
});


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