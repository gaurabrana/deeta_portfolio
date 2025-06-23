<?php
require 'connect.php';
header('Content-Type: application/json');

$response = [
    'success' => false,
    'errors' => [],
    'data' => []
];

try {
    // === Setup ===
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/quicktime'];
    $maxVideoSize = 40 * 1024 * 1024; // 50MB

    $imageDir = '../assets/images/gallery_uploads/image/';
    $videoDir = '../assets/images/gallery_uploads/video/';

    try {
        ensureDirectoryExists('../assets/images/gallery_uploads/image/');
        ensureDirectoryExists('../assets/images/gallery_uploads/video/');
    } catch (RuntimeException $e) {
        error_log($e->getMessage());
        die("System error: Please contact administrator.");
    }

    $caption = '';
    $section_id = $_POST['section_id'] ?? null;
    $videoSource = $_POST['videoSource'] ?? null;

    if (!$section_id) {
        throw new Exception('Missing section ID.');
    }

    // === Main Entry Points ===
    if ($videoSource === 'youtube') {
        handleYouTubeUpload($conn, $_POST['videoUrl'] ?? '', $caption, $section_id, $response);
    } else if (!empty($_FILES['videoUpload'])) {
        handleVideoUpload($conn, $_FILES['videoUpload'], $allowedVideoTypes, $maxVideoSize, $videoDir, $caption, $section_id, $response);
    } else if (!empty($_FILES['imageUpload'])) {
        handleImageUpload($conn, $_FILES['imageUpload'], $allowedImageTypes, $imageDir, $caption, $section_id, $response);
    }
    $response['success'] = empty($response['errors']);
} catch (Exception $e) {
    $response['errors'][] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
exit;


/// ========== Functions ==========

function insertUploadRecord($conn, $path, $caption, $position, $mediaType)
{
    $stmt = $conn->prepare("INSERT INTO uploads (path, caption, position, media_type) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new RuntimeException("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $path, $caption, $position, $mediaType);

    if (!$stmt->execute()) {
        throw new RuntimeException("Execute failed: " . $stmt->error);
    }

    // Correct way to get the inserted ID
    $insertId = $conn->insert_id;

    $stmt->close();

    return $insertId;
}

function linkSectionUpload($conn, $section_id, $upload_id)
{
    $stmt = $conn->prepare("INSERT INTO section_upload (section_id, upload_id) VALUES (?, ?)");
    $stmt->execute([$section_id, $upload_id]);
}

function addUploadResponse(&$response, $upload_id, $path, $caption, $mediaType, $section_id, $position = 'left')
{
    $response['data'][] = [
        'upload_id' => $upload_id,
        'path' => $path,
        'caption' => $caption,
        'position' => $position,
        'media_type' => $mediaType,
        'section_id' => $section_id
    ];
}

function handleYouTubeUpload($conn, $url , $caption, $section_id, &$response)
{
    if (!preg_match('/(youtu\.be\/|youtube\.com\/(watch\?v=|embed\/|v\/|shorts\/))([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        $response['errors'][] = 'Invalid YouTube URL.';
        return;
    }

    // Extract the video ID (in capturing group 3)
    $videoId = $matches[3];

    // Store only the video ID instead of full URL or caption if you want:
    $upload_id = insertUploadRecord($conn, $url, $videoId, 'left', 'video');

    // Link the upload with the section
    linkSectionUpload($conn, $section_id, $upload_id);

    // Use $videoId in place of $caption when adding response if desired
    addUploadResponse($response, $upload_id, $videoId, $caption, 'video', $section_id);
}

function handleVideoUpload($conn, $files, $allowedTypes, $maxSize, $targetDir, $caption, $section_id, &$response)
{
    foreach ($files['name'] as $i => $name) {
        $tmp = $files['tmp_name'][$i];
        $type = $files['type'][$i];
        $size = $files['size'][$i];

        if (!in_array($type, $allowedTypes)) {
            $response['errors'][] = "$name: Invalid video type.";
            continue;
        }

        if ($size > $maxSize) {
            $response['errors'][] = "$name: Video file too large (max 50MB).";
            continue;
        }

        // Changed filename generation only
        $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
        $filename = 'video_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExtension;
        $destination = $targetDir . $filename;

        if (!move_uploaded_file($tmp, $destination)) {
            $response['errors'][] = "$name: Failed to upload video.";
            continue;
        }

        $upload_id = insertUploadRecord($conn, $filename, $caption, 'left', 'video');
        linkSectionUpload($conn, $section_id, $upload_id);
        addUploadResponse($response, $upload_id, $filename, $caption, 'video', $section_id);
    }
}

function handleImageUpload($conn, $files, $allowedTypes, $targetDir, $caption, $section_id, &$response)
{
    foreach ($files['name'] as $i => $name) {
        $tmp = $files['tmp_name'][$i];
        $type = $files['type'][$i];

        if (!in_array($type, $allowedTypes)) {
            $response['errors'][] = "$name: Invalid image type.";
            continue;
        }

        $filename = uniqid('img_') . '_' . basename($name);
        $destination = $targetDir . $filename;

        if (!move_uploaded_file($tmp, $destination)) {
            $response['errors'][] = "$name: Failed to upload image.";
            continue;
        }

        $upload_id = insertUploadRecord($conn, $filename, $caption, 'left', 'image');
        linkSectionUpload($conn, $section_id, $upload_id);
        addUploadResponse($response, $upload_id, $filename, $caption, 'image', $section_id);
    }
}

function ensureDirectoryExists($path)
{
    if (!file_exists($path)) {
        if (!mkdir($path, 0755, true)) {
            throw new RuntimeException("Failed to create directory: $path");
        }
    }
    if (!is_writable($path)) {
        throw new RuntimeException("Directory not writable: $path");
    }
    return true;
}