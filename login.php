<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login As Admin</title>
    <?php
    session_start();

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header("Location: about_me.php");
        exit;  // Always exit after redirect
    }

    include("base/header.php");

    ?>

    <link href="assets/css/about_us.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="preloader" id="preloader">
        <img src="assets/images/preloader.gif" alt="Loading..." />
    </div>
    <!-- Header Section -->

    <div class="container d-flex justify-content-center align-items-center min-vh-100" id="login-container">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Admin Login</h3>
                <p class="text-muted small">Please enter your credentials</p>
            </div>
            <form method="POST" id="login-form">
                <div class="mb-3">
                    <label for="adminUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="adminUsername" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="adminPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="adminPassword" name="password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div id="loginStatus" class="text-center mb-3 text-danger" style="display: none;"></div>
                <div class="text-center">
                    <a href="reset_password.php#reset-container" class="text-decoration-none small">Forgot Password?</a>
                </div>

            </form>
        </div>
    </div>


    <?php
    include("base/footer.php");
    ?>

</body>

</html>