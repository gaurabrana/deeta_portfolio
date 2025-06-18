$(window).on('load', function () {

});

$(document).ready(function () {
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
                        const sectionEl = document.getElementById(data.section_slug); // ensure this ID exists
                        if (sectionEl) {
                            sectionEl.insertAdjacentHTML('beforeend', data.html);
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

