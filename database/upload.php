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
            $existingUpload = getExistingUpload($sectionId, $input['upload_id']);

            if ($existingUpload) {
                // Update existing record
                updateUpload(
                    $existingUpload['path'],
                    $input['caption'],
                    $existingUpload['media_type'],
                    $input['position'],
                    $input['upload_id'],
                    $input['heading']
                );

                $uploadResult = [
                    'filename' => $existingUpload['path'],
                    'media_type' => $existingUpload['media_type'],
                    'unlinkSuccess' => null,
                    'existingPath' => null,
                    'upload_id' => $input['upload_id']
                ];
            } else {
                // Insert a new record even without a file (store null path)
                $emptyFilename = '';
                $mediaType = null; // Or 'text-only', based on your logic
                $newUploadId = insertUploadRecord($sectionId, $emptyFilename, $input['caption'], $mediaType, $input['position'], $input['heading']);

                $uploadResult = [
                    'filename' => $emptyFilename,
                    'media_type' => $mediaType,
                    'unlinkSuccess' => null,
                    'existingPath' => null,
                    'upload_id' => $newUploadId
                ];
            }

            $output = prepareSuccessResponse($output, $uploadResult, $input);
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
        'upload_id' => $postData['upload_id'] ?? '',
        'new_upload' => empty($postData['upload_id']),
        'heading' => trim($postData['heading'] ?? ''),
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

    if (!isset($file['tmp_name']) || !file_exists($file['tmp_name'])) {
        throw new Exception('Invalid file upload.');
    }

    if ($file['size'] > $maxSize) {
        throw new Exception('File too large. Max 50MB allowed.');
    }

    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/quicktime'];
    $allowedPdfTypes = ['application/pdf'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!$mime) {
        throw new Exception('Unable to detect file type.');
    }

    if (in_array($mime, $allowedImageTypes)) {
        return ['media_type' => 'image', 'mime' => $mime];
    } elseif (in_array($mime, $allowedVideoTypes)) {
        return ['media_type' => 'video', 'mime' => $mime];
    } elseif (in_array($mime, $allowedPdfTypes)) {
        return ['media_type' => 'pdf', 'mime' => $mime];
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

    $newUploadId = $input['upload_id'];

    // Generate unique filename
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $targetFile = $uploadDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
        throw new Exception('Error saving file.');
    }

    // Check for existing upload
    $existingUpload = getExistingUpload($sectionId, $input['upload_id']);
    $existingPath = $existingUpload ? (!empty($existingPath) && file_exists($existingPath)) ? $uploadDir . $existingUpload['path'] : null : null;

    // Update or insert record
    if ($existingUpload) {
        updateUpload($filename, $input['caption'], $mediaType, $input['position'], $input['upload_id'], $input['heading']);
    } else {
        $newUploadId = insertUploadRecord($sectionId, $filename, $input['caption'], $mediaType, $input['position'], $input['heading']);
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
        'upload_id' => $newUploadId
    ];
}

// Get existing upload for section
function getExistingUpload($sectionId, $uploadId)
{
    if (!isset($uploadId) || empty($uploadId)) {
        return null;
    }

    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            u.id,
            u.path, 
            u.media_type,
            u.caption,
            u.position         
        FROM 
            uploads u
        INNER JOIN 
            section_upload su ON u.id = su.upload_id
        WHERE 
            su.section_id = ? 
            AND u.id = ?
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $sectionId, $uploadId);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    return $result->fetch_assoc();
}

// Update existing upload record
function updateUpload($filename, $caption, $mediaType, $position, $uploadId, $heading)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE uploads SET path = ?, caption = ?, media_type = ?, position = ?, heading = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $filename, $caption, $mediaType, $position, $heading, $uploadId, );

    if (!$stmt->execute()) {
        throw new Exception('Database update error.');
    }
}

// Insert new upload record
function insertUploadRecord($sectionId, $filename, $caption, $mediaType, $position, $heading)
{
    global $conn;

    // Insert into uploads table first
    $query = "INSERT INTO uploads (path, caption, media_type, position, heading) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $filename, $caption, $mediaType, $position, $heading);

    if (!$stmt->execute()) {
        throw new Exception('Failed to insert into uploads table: ' . $conn->error);
    }

    // Get the auto-incremented upload_id
    $uploadId = $conn->insert_id;

    // Insert into section_upload junction table
    $stmt = $conn->prepare("INSERT INTO section_upload (section_id, upload_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $sectionId, $uploadId);

    if (!$stmt->execute()) {
        throw new Exception('Failed to insert into section_upload table: ' . $conn->error);
    }

    return $uploadId; // Return the new upload ID for reference
}

// Prepare success response
function prepareSuccessResponse($output, $uploadResult, $input)
{
    $output['success'] = true;
    $output['message'] = 'Section updated successfully.';
    $output['path'] = $uploadResult['filename'];
    $output['media_type'] = $uploadResult['media_type'];
    $output['caption'] = htmlspecialchars($input['caption']);
    $output['heading'] = htmlspecialchars($input['heading']);
    $output['position'] = $input['position'];
    $output['section_id'] = $input['section_id'];
    $output['section_slug'] = $input['section_slug'];
    $output['upload_id'] = $uploadResult['upload_id'];

    $newUpload = $input['new_upload'];
    $output['new_upload'] = $newUpload;

    $tempUpload = [
        'upload_id' => $uploadResult['upload_id'], // Use existing ID or generate a temporary one
        'path' => $uploadResult['filename'],
        'caption' => $input['caption'],
        'position' => $input['position'],
        'media_type' => $uploadResult['media_type'],
        'heading' => $input['heading']
    ];

    // Capture the output of renderSingleMediaItem
    if (empty($uploadResult['media_type']) || $uploadResult['media_type'] !== "pdf") {
        ob_start();
        buildSingleContent($input['section_id'], $tempUpload, false);
        $output['content_html'] = ob_get_clean();
    }

    if ($newUpload) {
        // Capture the output of manage content html
        if (empty($uploadResult['media_type']) || $uploadResult['media_type'] !== "pdf") {
            ob_start();
            buildManageContentForm($input['page_slug'], $input['section_slug'], $input['section_id'], $tempUpload);
            $output['manage_content_html'] = ob_get_clean();
        }
    }

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
    } else {
        $output['file_delete_error'] = null;
        $output['file_deleted'] = null;
        $output['file_exists_but_not_deleted'] = null;
        $output['file_missing'] = null;
    }

    return $output;
}