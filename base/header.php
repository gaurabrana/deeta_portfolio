<?php
// Determine the current page
$current_page = basename($_SERVER['PHP_SELF']);
include('helper.php');
include('database/connect.php');
?>

<head>
    <!-- Standard Favicon -->
    <link rel="icon" href="assets/images/icons/favicon.ico" type="image/x-icon">

    <!-- PNG Icons for Modern Browsers -->
    <link rel="icon" href="assets/images/icons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="assets/images/icons/favicon-16x16.png" sizes="16x16" type="image/png">

    <!-- Apple Touch Icon for iOS Devices -->
    <link rel="apple-touch-icon" href="assets/images/icons/apple-touch-icon.png">

    <!-- Android Chrome Icons -->
    <link rel="icon" href="assets/images/icons/android-chrome-192x192.png" sizes="192x192" type="image/png">
    <link rel="icon" href="assets/images/icons/android-chrome-512x512.png" sizes="512x512" type="image/png">
</head>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<!-- Custom Style -->
<link rel="stylesheet" href="assets/css/style.css">
<!-- Bootstrap Icon Style -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="assets/css/aos.css">

<nav class="navbar navbar-light bg-green py-3 navbar-expand-md">
    <div class="container flex-column">
        <!-- Logo and Collapse Button (Adjusted for medium screen) -->
        <div class="d-flex justify-content-center w-100 align-items-center position-relative">
            <!-- Centered Logo -->
            <a class="navbar-brand text-white fs-3 text-center" href="about_me.php?page=who_am_i">
                <img src="assets/images/logo.png" height="100" alt="Dream Big Logo" />
            </a>
            <!-- Navbar Toggle Button -->
            <button class="navbar-toggler position-absolute end-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>


        <!-- Navigation Below Logo -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <?php
            renderNavbar($conn);
            ?>
        </div>
    </div>
</nav>