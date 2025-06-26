<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scouts</title>
    <?php
    include("base/header.php");
    ?>

</head>

<body>
    <div class="preloader" id="preloader">
        <img src="assets/images/preloader.gif" alt="Loading..." />
    </div>
    <!-- Header Section -->


    <div class="container-fluid content-section">
        <div class="all-contents">

            <div class="container content-sections">
                <?php
                $sectionDTO = loadSectionPageDTO($conn);
                if ($sectionDTO) {
                    buildSectionContents($conn, $sectionDTO);
                }
                ?>

            </div>

        </div>
    </div>
    <?php
    include("base/footer.php");
    ?>

</body>

</html>