// Image preview update
const imageInput = document.getElementById('image');
const preview = document.getElementById('imagePreview');

if (imageInput && preview) {
    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.parentElement.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.parentElement.style.display = 'none';
        }
    });
}

// jQuery AJAX form submission
$(document).ready(function () {
    $('#infoUpdateForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'database/update_quote.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#updateStatus')
                    .show()
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .text("Info updated successfully!");

                setTimeout(() => $('#updateStatus').fadeOut(), 3000);

                $('#image').val('');

                function splitHeading(heading) {
                    const words = heading.trim().split(/\s+/);
                    const half = Math.ceil(words.length / 2);
                    return {
                        part1: words.slice(0, half).join(' '),
                        part2: words.slice(half).join(' ')
                    };
                }

                const headingParts = splitHeading(response.heading);
                $('.col-md-5.text-start h2.mb-1').text(headingParts.part1);
                $('.col-md-5.text-start h2.mb-3').text(headingParts.part2);
                $('.quote-text').text('"' + response.quote + '"');

                const path = "assets/images/uploads/index/" + response.imagePath;
                $('.col-md-5.text-center img').attr('src', path + '?t=' + new Date().getTime());
            },
            error: function () {
                $('#updateStatus')
                    .show()
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text("Update failed.");
                setTimeout(() => $('#updateStatus').fadeOut(), 3000);
            }
        });
    });
});
