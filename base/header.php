<?php
// Determine the current page
$current_page = basename($_SERVER['PHP_SELF']);
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
                    <li class="nav-item mx-2 <?php echo $current_page == 'home.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li
                        class="nav-item mx-2 dropdown about-nav-item <?php echo $current_page == 'about_us.php' ? 'active-link' : ''; ?>">                        
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About Us
                        </a>
                        <ul class="about-dropdown dropdown-menu">
                            <li><a class="dropdown-item" href="about_us.php#who-are-we">Who are we ?</a></li>
                            <li><a class="dropdown-item" href="about_us.php#our-mission">Mission</a></li>
                            <li><a class="dropdown-item" href="about_us.php#our-vision">Vision</a></li>
                            <li><a class="dropdown-item" href="about_us.php#core-values">Core Values</a></li>
                            <li><a class="dropdown-item" href="about_us.php#our-story">Story</a></li>
                            <li><a class="dropdown-item" href="about_us.php#approach">Approach</a></li>
                            <li><a class="dropdown-item" href="about_us.php#our-impact">Impact</a></li>
                        </ul>
                    </li>

                    <li
                        class="nav-item dropdown mx-2 services-nav-item <?php echo $current_page == 'services.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Services
                        </a>
                        <ul class="dropdown-menu">
                            <!-- College Admission Counselling -->
                            <li>
                                <a class="dropdown-item" href="services.php#College_Admission_Counselling">
                                    College Admission Counselling
                                </a>
                            </li>

                            <!-- Essays Review -->
                            <li>
                                <a class="dropdown-item" href="services.php#Essays_Review">
                                    Essays Review
                                </a>
                            </li>

                            <!-- Scholarship and Financial Aid (FAFSA) Counselling -->
                            <li>
                                <a class="dropdown-item" href="services.php#Financial_Aid_Counselling">
                                    Scholarship & Financial Aid (FAFSA) Counselling
                                </a>
                            </li>

                            <!-- Extra-Curricular activities Counselling -->
                            <li>
                                <a class="dropdown-item" href="services.php#Extra_Curricular">
                                    Extra-Curricular activities Counselling
                                </a>
                            </li>

                            <!-- Interview Counselling -->
                            <li>
                                <a class="dropdown-item" href="services.php#Interview_Counselling">
                                    Interview Counselling
                                </a>
                            </li>

                            <!-- Career Navigation Counselling -->
                            <li>
                                <a class="dropdown-item" href="services.php#Career_Navigation_Counselling">
                                    Career Navigation Counselling
                                </a>
                            </li>

                            <!-- Research Paper Counselling -->
                            <li>
                                <a class="dropdown-item" href="services.php#Research_Paper_Counselling">
                                    Research Paper Counselling
                                </a>
                            </li>

                            <!-- Co-op and Internship Opportunities -->
                            <li>
                                <a class="dropdown-item" href="services.php#Co_Internship_Opportunities">
                                    Co-op and Internship Opportunities
                                </a>
                            </li>

                            <!-- SAT/ACT Coaching -->
                            <li>
                                <a class="dropdown-item" href="services.php#SAT_ACT_Coaching">
                                    SAT/ACT Coaching
                                </a>
                            </li>

                            <!-- Building Communication Skills -->
                            <li>
                                <a class="dropdown-item" href="services.php#Building_Communication_Skills">
                                    Building Communication Skills
                                </a>
                            </li>

                            <!-- Leadership Guidance -->
                            <li>
                                <a class="dropdown-item" href="services.php#Leadership_Guidance">
                                    Leadership Guidance
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item mx-2 <?php echo $current_page == 'reviews.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="reviews.php">Reviews</a>
                    </li> -->
                    <li class="nav-item mx-2 <?php echo $current_page == 'message.php' ? 'active-link' : ''; ?>">
                        <a class="nav-link" href="givingback.php">Giving Back</a>
                    </li>                   
                    <li
                        class="nav-item mx-2 dropdown gallery-nav-item <?php echo ($current_page == 'gallery_image.php' || $current_page == 'gallery_video.php') ? 'active-link' : ''; ?>">                        
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Gallery
                        </a>
                        <ul class="gallery-dropdown dropdown-menu">
                            <li><a class="dropdown-item" href="gallery_image.php">Images</a></li>
                            <li><a class="dropdown-item" href="gallery_video.php">Videos</a></li>                            
                        </ul>
                    </li>
                    <li  class="nav-item mx-2"><a class="nav-link" type="button" data-bs-toggle="modal" data-bs-target="#contactUs" class="footer-link">Contact Us</a></li>

            </div>
        </div>
    </div>
</nav>


<!-- Full-width dropdown container -->
<!-- <div class="services-dropdown-container dropdown-container">
 
                        <div
                            class="services-title-container <?php echo $current_page == 'services.php' ? 'active-link' : ''; ?>">
                            <span class="services-title">Our Services</span>
                            <hr class="title-line">
                        </div>
                        
                        <div class="services-items-container">
                            <ul class="services-dropdown">
                                <li><a class="dropdown-item" href="services.php#College_Admission_Counselling">College
                                        Admission Counselling</a></li>
                                <li><a class="dropdown-item" href="services.php#Essays_Review">Essays Review</a></li>
                                <li><a class="dropdown-item" href="services.php#Financial_Aid_Counselling">Financial Aid
                                        Counselling</a></li>
                                <li><a class="dropdown-item" href="services.php#Scholarship_Opportunities">Scholarship
                                        Opportunities</a></li>
                                <li><a class="dropdown-item" href="services.php#Co_Internship_Opportunities">Co-op and
                                        Internship Opportunities</a></li>
                                <li><a class="dropdown-item" href="services.php#Career_Navigation_Counselling">Career
                                        Navigation Counselling</a></li>
                                <li><a class="dropdown-item" href="services.php#SAT_ACT_Coaching">SAT/ACT Coaching</a>
                                </li>
                                <li><a class="dropdown-item" href="services.php#Building_Communication_Skills">Building
                                        Communication Skills</a></li>
                                <li><a class="dropdown-item" href="services.php#Leadership_Guidance">Leadership
                                        Guidance</a></li>
                            </ul>

                        </div>
                    </div> -->


<!-- <div class="about-dropdown-container dropdown-container">                        
                        <div
                            class="services-title-container <?php echo $current_page == 'about_us.php' ? 'active-link' : ''; ?>">
                            <span class="services-title">About Us</span>
                            <hr class="title-line">
                        </div>
                        
                        <div class="services-items-container">
                            <ul class="services-dropdown">
                                <li><a class="dropdown-item" href="about_us.php#our-mission">Our Mission</a></li>
                                <li><a class="dropdown-item" href="about_us.php#our-vision">Our Vision</a></li>
                                <li><a class="dropdown-item" href="about_us.php#our-story">Our Story</a></li>
                                <li><a class="dropdown-item" href="about_us.php#approach">Our Approach</a></li>
                                <li><a class="dropdown-item" href="about_us.php#our-impact">Our Impact</a></li>
                            </ul>
                        </div>
                    </div> -->