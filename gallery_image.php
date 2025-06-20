<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/fancybox.css" />
    <link rel="stylesheet" href="assets/css/gallery.css" />
    <title>Gallery</title>
    <?php include("base/header.php"); ?>
</head>

<body>
    <div class="preloader" id="preloader">
        <img src="assets/images/preloader.gif" alt="Loading..." />
    </div>
    <div class="container-fluid content-section">
        <div class="all-contents">
            <center class="gallery-text-container">
                <h4>Gallery Photos </h4>
            </center>
            <?php
            $section_id = getSectionId($conn, 'image');
            // Display the form and gallery
            echo generateGalleryAccordionForm($section_id, 'image');
            ?>
            <div class="gallery-container">
                <ul>
                    <?php
                    echo generateGalleryItems($conn, 'image');
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php include("base/footer.php"); ?>
    <script src="assets/js/fancybox.umd.js"></script>
    <script type="text/javascript">
        Fancybox.bind("[data-fancybox]", {
            hideScrollbar: false,
        });
    </script>
</body>

</html>