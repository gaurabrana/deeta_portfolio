$(document).ready(function () {
    $('.delete-media-btn').on('click', function () {
        const sectionId = $(this).data('upload-id');
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
                    if (sectionEl) {
                        sectionEl.innerHTML = '';
                        sectionContainerForm.clear;
                    }
                    if (response.file_deleted) {
                        statusBox.innerHTML = `<div class="text-success">Media record and file deleted successfully.</div>`;
                    } else if (response.file_delete_error) {
                        statusBox.innerHTML = `<div class="text-warning">Record deleted, but file could not be removed from server.</div>`;
                    } else if (response.file_missing) {
                        statusBox.innerHTML = `<div class="text-info">Record deleted. File was already missing.</div>`;
                    } else {
                        statusBox.innerHTML = `<div class="text-success">Record deleted (unknown file state).</div>`;
                    }
                } else {
                    statusBox.innerHTML = '<div class="text-danger">Failed to delete media: ' + (response.error || 'Unknown error') + '</div>';
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
                statusBox.innerHTML = '<div class="text-danger"> Error deleting media: ' + error + '</div > ';
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
            const formData = new FormData(this);

            if (!fileInput.files.length) {
                statusBox.innerHTML = '<div class="text-danger">Please select a file.</div>';
                return;
            }

            fetch('./database/upload.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())  // read response body as raw text
                .then(data => {
                    if (data.success) {
                        statusBox.innerHTML = `<div class="text-success">${data.message}</div>`;
                        form.reset();
                        document.getElementById(previewId).innerHTML = '';

                        // Append the new HTML to the section container
                        const sectionEl = document.getElementById('media-preview-container-' + data.sectionId); // ensure this ID exists
                        if (sectionEl) {
                            sectionEl.innerHTML = data.html;
                        }
                    } else {
                        statusBox.innerHTML = `<div class="text-danger">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    statusBox.innerHTML = '<div class="text-danger">Upload failed.</div>';
                });
        });
    });
});

