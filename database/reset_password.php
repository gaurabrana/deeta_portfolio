<?php
include("connect.php"); // Adjust as needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];

    if (empty($username) || empty($newPassword)) {
        echo "All fields are required.";
        exit;
    }

    // Check if admin exists
    $stmt = $conn->prepare("SELECT id FROM user WHERE username = ? and role = 'admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
        $updateStmt->bind_param("ss", $newHashedPassword, $username);
        if ($updateStmt->execute()) {
            echo "Password updated successfully.";
        } else {
            echo "Failed to update password.";
        }
    } else {
        echo "Admin username not found.";
    }
} else {
    echo "Invalid request.";
}
