<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['upload_id']) || !is_numeric($data['upload_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid upload ID']);
    exit;
}

$uploadId = (int) $data['upload_id'];
$isGalleryItem = isset($data['delete_type']) && $data['delete_type'] === 'gallery';

include 'connect.php';

// Fetch path
$stmt = $conn->prepare("SELECT path FROM uploads WHERE id = ? LIMIT 1");
$stmt->execute([$uploadId]);
$result = $stmt->get_result();
$upload = $result->fetch_assoc();

if (!$upload) {
    echo json_encode(['success' => false, 'error' => 'Upload not found']);
    exit;
}

$filePath = __DIR__ . '/../assets/images/uploads/' . $upload['path'];
if ($isGalleryItem) {
    $type = $data['type']; // expected to be 'image' or 'video'

    // Sanitize $type and make sure it's valid
    if (in_array($type, ['image', 'video'])) {
        $filePath = __DIR__ . '/../assets/images/gallery_uploads/' . $type . '/' . $upload['path'];
    }
}

// Delete DB record
$stmt = $conn->prepare("DELETE FROM section_upload WHERE upload_id = ?");
$success = $stmt->execute([$uploadId]);

$stmt = $conn->prepare("DELETE FROM uploads WHERE id = ?");
$success = $stmt->execute([$uploadId]);

$response = ['success' => $success];

if (empty($upload['path']) || !isset($upload['path'])) {
    echo json_encode($response);
    exit;
}
// Delete file from disk
$unlinkSuccess = null; // initialize

if ($success && file_exists($filePath)) {
    $unlinkSuccess = unlink($filePath);
}

if ($unlinkSuccess === false) {
    $response['file_delete_error'] = 'File exists but could not be deleted.';
} elseif ($unlinkSuccess === true) {
    $response['file_deleted'] = true;
} elseif (file_exists($filePath)) {
    $response['file_exists_but_not_deleted'] = true;
} else {
    $response['file_missing'] = 'File not found, nothing to delete.';
}

echo json_encode($response);
