<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research</title>
    <?php
    include("base/header.php");
    ?>
    <link href="assets/css/about_us.css" rel="stylesheet" type="text/css" />
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
                $query = "SELECT s.id, s.slug, s.title, p.slug as pageSlug 
          FROM sections s 
          JOIN pages p ON s.page_id = p.id 
          WHERE p.slug = 'research'";

                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                        renderMediaSection($conn, $row['pageSlug'], $row['id'], $row['slug'], $row['title']);
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