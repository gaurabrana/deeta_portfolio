<?php
header('Content-Type: application/json');
require_once 'connect.php';

if (!isset($_SESSION['logged_in'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$heading = $_POST['heading'] ?? '';
$quote = $_POST['quote'] ?? '';
$imagePath = null;
$targetFilename = null;

// Handle file upload if exists
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $targetDir = "../assets/images/uploads/index/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $filename = basename($_FILES["image"]["name"]);
    $targetFilename = time() . "_" . $filename;
    $targetFile = $targetDir . $targetFilename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imagePath = $targetFilename;
    }
}

// Check if a record with id=1 exists, and get current image path
$oldImage = null;
$checkStmt = $conn->prepare("SELECT path, COUNT(*) FROM index_info WHERE id = 1");
$checkStmt->execute();
$checkStmt->bind_result($oldImage, $count);
$checkStmt->fetch();
$checkStmt->close();

try {
    if ($count > 0) {
        // If new image uploaded and old image exists, delete old image file
        if ($imagePath && $oldImage) {
            $oldImagePath = "../assets/images/uploads/index/" . $oldImage;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // UPDATE
        if ($imagePath) {
            $stmt = $conn->prepare("UPDATE index_info SET heading = ?, path = ?, quote = ? WHERE id = 1");
            $stmt->bind_param("sss", $heading, $imagePath, $quote);
        } else {
            $stmt = $conn->prepare("UPDATE index_info SET heading = ?, quote = ? WHERE id = 1");
            $stmt->bind_param("ss", $heading, $quote);
        }
    } else {
        // INSERT
        if ($imagePath) {
            $stmt = $conn->prepare("INSERT INTO index_info (id, heading, path, quote) VALUES (1, ?, ?, ?)");
            $stmt->bind_param("sss", $heading, $imagePath, $quote);
        } else {
            $stmt = $conn->prepare("INSERT INTO index_info (id, heading, quote) VALUES (1, ?, ?)");
            $stmt->bind_param("ss", $heading, $quote);
        }
    }

    if ($stmt->execute()) {
        // After successful update, fetch latest data
        $stmts = $conn->prepare("SELECT heading, path, quote FROM index_info WHERE id = 1");
        $stmts->execute();
        $result = $stmts->get_result();
        $data = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "heading" => $data['heading'],
            "quote" => $data['quote'],
            "imagePath" => $data['path'],
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database execution failed."]);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Exception: " . $e->getMessage()]);
}

$conn->close();
