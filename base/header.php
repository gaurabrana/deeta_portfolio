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

    <!-- Manifest File for Progressive Web Apps -->
    <link rel="manifest" href="assets/images/icons/site.webmanifest">
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
<link href="assets/css/privacy.css" type="text/css" rel="stylesheet" />
<link href="assets/css/terms_and_conditions.css" type="text/css" rel="stylesheet" />
<link href="assets/css/contact_us.css" type="text/css" rel="stylesheet" />

<nav class="navbar navbar-light bg-green py-3 navbar-expand-md">
    <div class="container flex-column">
        <!-- Logo and Collapse Button (Adjusted for medium screen) -->
        <div class="d-flex justify-content-center w-100 align-items-center position-relative">
            <!-- Centered Logo -->
            <a class="navbar-brand text-white fs-3 text-center" href="about_us.php">
                <img src="assets/images/dream_big_logo_green.png" height="100" alt="Dream Big Logo" />
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
            <div class="navbar-collapse justify-content-center mt-3">
                <ul class="navbar-nav">
                    <li
                        class="nav-item mx-2 dropdown about-nav-item <?php echo $current_page == 'about_me.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About Me
                        </a>
                        <ul class="about-dropdown dropdown-menu">
                            <li class="dropdown-submenu position-relative dropend">
                                <a class="dropdown-item dropdown-toggle" href="about_me.php#who-am-i">Who am I</a>
                                <ul class="dropdown-menu sub-dropdown">
                                    <li><a class="dropdown-item" href="about_me.php#introduction">Introduction</a></li>
                                    <li><a class="dropdown-item" href="about_me.php#leader">Am I a Leader</a></li>
                                    <li><a class="dropdown-item" href="about_me.php#resilient">Am I Resilient</a></li>
                                    <li><a class="dropdown-item" href="about_me.php#empathy">Do I Have an Empathy</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" href="about_me.php#resume">Resume</a></li>
                        </ul>
                    </li>

                    <li
                        class="nav-item dropdown mx-2 school-nav-item <?php echo $current_page == 'schools.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Schools Attended
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Morning Side Elementary School -->
                            <li>
                                <a class="dropdown-item" href="schools.php#morning_side_elementary_school">
                                    Morning Side Elementary School
                                </a>
                            </li>

                            <!-- Pearson Middle School -->
                            <li>
                                <a class="dropdown-item" href="schools.php#pearson_middle_school">
                                    Pearson Middle School
                                </a>
                            </li>

                            <!-- Reedy High School -->
                            <li>
                                <a class="dropdown-item" href="schools.php#reedy_high_school">
                                    Reedy High School
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="nav-item dropdown mx-2 sports-nav-item <?php echo $current_page == 'sports.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Sports
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Soccer -->
                            <li>
                                <a class="dropdown-item" href="sports.php#soccer">
                                    Soccer
                                </a>
                            </li>

                            <!-- Swimming -->
                            <li>
                                <a class="dropdown-item" href="sports.php#swimming">
                                    Swimming
                                </a>
                            </li>

                            <!-- Basketball -->
                            <li>
                                <a class="dropdown-item" href="sports.php#basketball">
                                    Basketball
                                </a>
                            </li>

                            <!-- Volleyball -->
                            <li>
                                <a class="dropdown-item" href="sports.php#volleyball">
                                    Volleyball
                                </a>
                            </li>

                            <!-- Track -->
                            <li>
                                <a class="dropdown-item" href="sports.php#track">
                                    Track
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item dropdown mx-2 scouting-nav-item <?php echo $current_page == 'scouting.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Scouting
                        </a>
                        <ul class="dropdown-menu">
                            <!-- Girls Scout -->
                            <li>
                                <a class="dropdown-item" href="scouting.php#girls_scount">
                                    Girls Scout
                                </a>
                            </li>

                            <!-- Boys Scout -->
                            <li>
                                <a class="dropdown-item" href="scouting.php#boys_scout">
                                    Boys Scout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item mx-2 <?php echo $current_page == 'research.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="research.php">Research</a>
                    </li>
                    <li class="nav-item mx-2 <?php echo $current_page == 'moment_of_truth.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="moment_of_truth.php">Moment Of Truth</a>
                    </li>
                    <li
                        class="nav-item dropdown mx-2 givingback-nav-item <?php echo $current_page == 'givingback.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                            href="#">Giving Back</a>
                        <ul class="dropdown-menu">
                            <!-- Giving Back to My School -->
                            <li>
                                <a class="dropdown-item" href="givingback.php#giving_back_to_my_school">
                                    Giving Back to My School
                                </a>
                            </li>

                            <!-- Giving Back to My Community -->
                            <li>
                                <a class="dropdown-item" href="givingback.php#giving_back_to_my_community">
                                    Giving Back to My Community
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item mx-2 dropdown gallery-nav-item <?php echo ($current_page == 'gallery_image.php' || $current_page == 'gallery_video.php') ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Gallery
                        </a>
                        <ul class="gallery-dropdown dropdown-menu">
                            <li><a class="dropdown-item" href="gallery_image.php">Photos</a></li>
                            <li><a class="dropdown-item" href="gallery_video.php">Videos</a></li>
                        </ul>
                    </li>
            </div>
        </div>
    </div>
</nav>