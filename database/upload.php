<?php
require 'connect.php'; // Connect to the database using $conn
include '../helper.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

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
$position = $_POST['position'] ?? 'left'; // default to 'left'

// Check if required fields are present
if (!$page_slug || !$section_slug) {
    $output['message'] = 'Missing page or section.';
    echo json_encode($output);
    exit;
}

// Check if a file was uploaded and has no error
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $output['message'] = 'File upload failed.';
    echo json_encode($output);
    exit;
}

// === File Validation ===

// Define max file size (50MB)
$maxSize = 50 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    $output['message'] = 'File too large. Max 50MB allowed.';
    echo json_encode($output);
    exit;
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
    echo json_encode($output);
    exit;
}

// === Auto-create Page if missing ===
$stmt = $conn->prepare("SELECT id FROM pages WHERE slug = ?");
$stmt->bind_param("s", $page_slug);
$stmt->execute();
$result = $stmt->get_result();
$page = $result->fetch_assoc();

if (!$page) {
    $insertPage = $conn->prepare("INSERT INTO pages (slug, title) VALUES (?, ?)");
    $pageName = ucfirst(str_replace('-', ' ', $page_slug)); // generate friendly name
    $insertPage->bind_param("ss", $page_slug, $pageName);
    if (!$insertPage->execute()) {
        $output['message'] = 'Failed to create page.';
        echo json_encode($output);
        exit;
    }
    $page_id = $conn->insert_id;
} else {
    $page_id = $page['id'];
}

// === Auto-create Section if missing ===
$stmt = $conn->prepare("SELECT id FROM sections WHERE slug = ? AND page_id = ?");
$stmt->bind_param("si", $section_slug, $page_id);
$stmt->execute();
$result = $stmt->get_result();
$section = $result->fetch_assoc();

if (!$section) {
    $insertSection = $conn->prepare("INSERT INTO sections (slug, title, page_id) VALUES (?, ?, ?)");
    $sectionName = ucfirst(str_replace('-', ' ', $section_slug));
    $insertSection->bind_param("ssi", $section_slug, $sectionName, $page_id);
    if (!$insertSection->execute()) {
        $output['message'] = 'Failed to create section.';
        echo json_encode($output);
        exit;
    }
    $section_id = $conn->insert_id;
} else {
    $section_id = $section['id'];
}

// === File Upload Handling ===
$uploadDir = "../assets/images/uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate a unique file name for the upload
$ext = pathinfo($file["name"], PATHINFO_EXTENSION);
$filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
$targetFile = $uploadDir . $filename;

// Move the uploaded file to the target directory
if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
    $output['message'] = 'Error saving file.';
    echo json_encode($output);
    exit;
}

// === Database Insert for Uploads ===
$stmt = $conn->prepare("
    INSERT INTO uploads (section_id, path, caption, media_type, position)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("issss", $section_id, $filename, $caption, $media_type, $position);

if ($stmt->execute()) {
    $output['success'] = true;
    $output['message'] = ucfirst($media_type) . ' uploaded successfully.';
    $output['path'] = $filename;
    $output['media_type'] = $media_type;
    $output['caption'] = htmlspecialchars($caption);
    $output['position'] = $position;
    $output['section_slug'] = $section_slug;
    // Buffer the HTML output of your render function
    ob_start();
    renderSingleMediaItem( $section_slug, 'Section Label', $output);
    $html = ob_get_clean();

    // Add the rendered HTML to the response
    $output['html'] = $html;
} else {
    $output['message'] = 'Database error.';
}

// Return JSON response
echo json_encode($output);
exit;
?>