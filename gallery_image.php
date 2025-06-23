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
            <div class="accordion-form-container">
            <?php
            $section_id = getSectionId($conn, 'image');
            // Display the form and gallery
            $isAdminEditing = (isset($_SESSION) && isset($_SESSION['logged_in'])); // this will flag whether to show editing options or not
            if($isAdminEditing){
                echo generateGalleryAccordionForm($section_id, 'image');
            }
            ?>

            </div>

            <?php
            echo generateGalleryItems($conn, 'image');
            ?>
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