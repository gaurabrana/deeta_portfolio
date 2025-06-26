<?php
include("base/header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Admin Password</title>
    
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100"  id="reset-container">
        <div class="card shadow-lg p-4" style="max-width: 450px; width: 100%; border-radius: 1rem;">
            <div class="text-center mb-4">
                <h4 class="fw-bold">Reset Password</h4>
                <p class="text-muted small">Choose a new password</p>
            </div>
            <form id="reset-password-form">
                <div class="mb-3">
                    <label for="username" class="form-label">Admin Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div id="resetStatus" class="text-center mb-3 text-danger" style="display: none;"></div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-warning">Change Password</button>
                </div>
            </form>
            <a type="button" href="login.php#login-container" class="btn btn-secondary">Go to login screen</a>
        </div>
    </div>

    <?php include("base/footer.php"); ?>
</body>

</html>