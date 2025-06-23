<?php
header('Content-Type: application/json');
require_once 'connect.php';

try {
    // Sanitize and validate input
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        throw new Exception('Username and password are required.');
    }

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, password, role FROM user WHERE username = ? AND role = 'admin'");
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Success: set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['logged_in'] = true;  // Example extra flag

            echo json_encode(['status' => 'success', 'message' => 'Login successful!']);

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
        }
    } else {
        throw new Exception('Admin user not found.');
    }

    $stmt->close();
} catch (Exception $e) {
    // Close connection if open
    if (isset($stmt) && $stmt)
        $stmt->close();
    if (isset($conn) && $conn)
        $conn->close();

    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);

}
?>