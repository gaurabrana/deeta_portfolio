<?php
require 'connect.php'; // Connect to the database using $conn

// Initialize the output array for JSON response
$output = [
    'success' => false,
    'message' => '',
];

// === Input Collection and Basic Validation ===

// Retrieve POST data safely
$page_slug = $_POST['page_slug'] ?? '';
$section_slug = $_POST['section_slug'] ?? '';
$caption = trim($_POST['caption'] ?? '');
$file = $_FILES['media'] ?? null;

// Check if required fields are present
if (!$page_slug || !$section_slug) {
    $output['message'] = 'Missing page or section.';
    echo json_encode($output); exit;
}

// Check if a file was uploaded and has no error
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $output['message'] = 'File upload failed.';
    echo json_encode($output); exit;
}

// === File Validation ===

// Define max file size (50MB)
$maxSize = 50 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    $output['message'] = 'File too large. Max 50MB allowed.';
    echo json_encode($output); exit;
}

// Get MIME type of the uploaded file
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

// Define supported MIME types
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$allowedVideoTypes = ['video/mp4', 'video/webm', 'video/quicktime'];

$media_type = ''; // Will hold either 'image' or 'video'

// Identify media type based on MIME
if (in_array($mime, $allowedImageTypes)) {
    $media_type = 'image';
} elseif (in_array($mime, $allowedVideoTypes)) {
    $media_type = 'video';
} else {
    $output['message'] = 'Unsupported file type.';
    echo json_encode($output); exit;
}

// === Section Lookup ===

// Fetch the section ID using page_slug and section_slug
$stmt = $conn->prepare("
    SELECT s.id AS section_id FROM sections s
    JOIN pages p ON s.page_id = p.id
    WHERE p.slug = ? AND s.slug = ?
");
$stmt->bind_param("ss", $page_slug, $section_slug);
$stmt->execute();
$section = $stmt->get_result()->fetch_assoc();

// If no matching section is found, return error
if (!$section) {
    $output['message'] = 'Section not found.';
    echo json_encode($output); exit;
}

// === File Upload Handling ===

// Set directory where files will be saved
$uploadDir = "uploads/";

// Create directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate a unique file name
$ext = pathinfo($file["name"], PATHINFO_EXTENSION);
$filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
$targetFile = $uploadDir . $filename;

// Move the uploaded file to the target directory
if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
    $output['message'] = 'Error saving file.';
    echo json_encode($output); exit;
}

// === Database Insert ===

// Insert the media record into the `uploads` table
$stmt = $conn->prepare("
    INSERT INTO uploads (section_id, path, caption, media_type)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $section['section_id'], $targetFile, $caption, $media_type);

if ($stmt->execute()) {
    // If insert is successful, respond with success
    $output['success'] = true;
    $output['message'] = ucfirst($media_type) . ' uploaded successfully.';
    $output['media_url'] = $targetFile;
    $output['media_type'] = $media_type;
    $output['caption'] = htmlspecialchars($caption); // Sanitize for safety
} else {
    $output['message'] = 'Database error.';
}

// Return the output as JSON for AJAX handling
echo json_encode($output);
exit;