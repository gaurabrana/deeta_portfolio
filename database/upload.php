<?php
require 'connect.php';
include '../helper.php';
header('Content-Type: application/json');
// Execute the main function
handleMediaUpload();

// Main function to handle the upload process
function handleMediaUpload()
{
    $output = initializeOutput();

    try {
        // Validate and process input
        $input = validateAndProcessInput($_POST, $_FILES);

        // Handle page and section (create if needed)
        $pageId = handlePage($input['page_slug']);
        $sectionId = handleSection($input['section_slug'], $input['section_id'], $pageId);

        // Check if file uploaded
        $fileUploaded = ($input['file'] && $input['file']['error'] === UPLOAD_ERR_OK);

        if (!$fileUploaded) {
            
            // No file uploaded: check if upload record exists
            $existingUpload = getExistingUpload($sectionId);
            if (!$existingUpload) {
                throw new Exception('No file uploaded and no existing upload found for this section.');
            }

            // Update caption/position only, no file upload
            updateUpload($sectionId, $existingUpload['path'], $input['caption'], $existingUpload['media_type'], $input['position']);

            // Prepare success response with existing file info
            $output = prepareSuccessResponse($output, [
                'filename' => $existingUpload['path'],
                'media_type' => $existingUpload['media_type'],
                'unlinkSuccess' => null,
                'existingPath' => null,
            ], $input);

        } else {
            // File uploaded: validate file
            $fileInfo = validateUploadedFile($input['file']);

            // Process the file upload
            $uploadResult = processFileUpload($input['file'], $fileInfo['media_type'], $sectionId, $input);

            // Prepare success response
            $output = prepareSuccessResponse($output, $uploadResult, $input);
        }

    } catch (Exception $e) {
        $output['message'] = $e->getMessage();
    }

    echo json_encode($output);
    exit;
}

// Initialize the output array
function initializeOutput()
{
    return [
        'success' => false,
        'message' => '',
    ];
}

// Validate and process input data
function validateAndProcessInput($postData, $fileData)
{
    $input = [
        'page_slug' => $postData['page_slug'] ?? '',
        'section_slug' => $postData['section_slug'] ?? '',
        'caption' => trim($postData['caption'] ?? ''),
        'file' => $fileData['media'] ?? null,
        'position' => $postData['position'] ?? 'left',
        'section_id' => $postData['section_id'] ?? '',
    ];

    if (empty($input['page_slug']) || empty($input['section_slug'])) {
        throw new Exception('Missing page or section.');
    }

    return $input;
}

// Validate the uploaded file
function validateUploadedFile($file)
{
    $maxSize = 50 * 1024 * 1024; // 50MB
    if ($file['size'] > $maxSize) {
        throw new Exception('File too large. Max 50MB allowed.');
    }

    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/quicktime'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (in_array($mime, $allowedImageTypes)) {
        return ['media_type' => 'image', 'mime' => $mime];
    } elseif (in_array($mime, $allowedVideoTypes)) {
        return ['media_type' => 'video', 'mime' => $mime];
    }

    throw new Exception('Unsupported file type.');
}

// Handle page (create if doesn't exist)
function handlePage($pageSlug)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id FROM pages WHERE slug = ?");
    $stmt->bind_param("s", $pageSlug);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();

    if ($page) {
        return $page['id'];
    }

    // Create new page
    $insertPage = $conn->prepare("INSERT INTO pages (slug, title) VALUES (?, ?)");
    $pageName = ucfirst(str_replace('-', ' ', $pageSlug));
    $insertPage->bind_param("ss", $pageSlug, $pageName);

    if (!$insertPage->execute()) {
        throw new Exception('Failed to create page.');
    }

    return $conn->insert_id;
}

// Handle section (create if doesn't exist)
function handleSection($sectionSlug, $sectionId, $pageId)
{
    global $conn;

    if (!empty($sectionId)) {
        return $sectionId;
    }

    $stmt = $conn->prepare("SELECT id FROM sections WHERE slug = ? AND page_id = ?");
    $stmt->bind_param("si", $sectionSlug, $pageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();

    if ($section) {
        return $section['id'];
    }

    // Create new section
    $insertSection = $conn->prepare("INSERT INTO sections (slug, title, page_id) VALUES (?, ?, ?)");
    $sectionName = ucfirst(str_replace('-', ' ', $sectionSlug));
    $insertSection->bind_param("ssi", $sectionSlug, $sectionName, $pageId);

    if (!$insertSection->execute()) {
        throw new Exception('Failed to create section.');
    }

    return $conn->insert_id;
}

// Process file upload and database operations
function processFileUpload($file, $mediaType, $sectionId, $input)
{
    global $conn;

    $uploadDir = "../assets/images/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $targetFile = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
        throw new Exception('Error saving file.');
    }

    // Check for existing upload
    $existingUpload = getExistingUpload($sectionId);
    $existingPath = $existingUpload ? $uploadDir . $existingUpload['path'] : null;

    // Update or insert record
    if ($existingUpload) {
        updateUpload($sectionId, $filename, $input['caption'], $mediaType, $input['position']);
    } else {
        insertUpload($sectionId, $filename, $input['caption'], $mediaType, $input['position']);
    }

    // Clean up old file if it exists
    $unlinkSuccess = null;
    if ($existingPath && file_exists($existingPath)) {
        $unlinkSuccess = unlink($existingPath);
    }

    return [
        'filename' => $filename,
        'media_type' => $mediaType,
        'unlinkSuccess' => $unlinkSuccess,
        'existingPath' => $existingPath,
    ];
}

// Get existing upload for section
function getExistingUpload($sectionId)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, path, media_type FROM uploads WHERE section_id = ?");
    $stmt->bind_param("i", $sectionId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Update existing upload record
function updateUpload($sectionId, $filename, $caption, $mediaType, $position)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE uploads SET path = ?, caption = ?, media_type = ?, position = ? WHERE section_id = ?");
    $stmt->bind_param("ssssi", $filename, $caption, $mediaType, $position, $sectionId);

    if (!$stmt->execute()) {
        throw new Exception('Database update error.');
    }
}

// Insert new upload record
function insertUpload($sectionId, $filename, $caption, $mediaType, $position)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO uploads (section_id, path, caption, media_type, position) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $sectionId, $filename, $caption, $mediaType, $position);

    if (!$stmt->execute()) {
        throw new Exception('Database insert error.');
    }
}

// Prepare success response
function prepareSuccessResponse($output, $uploadResult, $input)
{
    $output['success'] = true;
    $output['message'] = 'Section updated successfully.';
    $output['path'] = $uploadResult['filename'];
    $output['media_type'] = $uploadResult['media_type'];
    $output['caption'] = htmlspecialchars($input['caption']);
    $output['position'] = $input['position'];
    $output['section_id'] = $input['section_id'];
    $output['section_slug'] = $input['section_slug'];

    // Handle file deletion status
    if (isset($uploadResult['unlinkSuccess'])) {
        if ($uploadResult['unlinkSuccess'] === false) {
            $output['file_delete_error'] = 'Existing file exists but could not be deleted.';
        } elseif ($uploadResult['unlinkSuccess'] === true) {
            $output['file_deleted'] = true;
        } elseif (file_exists($uploadResult['existingPath'])) {
            $output['file_exists_but_not_deleted'] = true;
        } else {
            $output['file_missing'] = 'Existing file could not be found to update.';
        }
    }

    return $output;
}