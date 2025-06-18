<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['section_id']) || !is_numeric($data['section_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid section ID']);
    exit;
}

$sectionId = (int) $data['section_id'];

include 'connect.php';

// Fetch path
$stmt = $conn->prepare("SELECT path FROM uploads WHERE section_id = ? LIMIT 1");
$stmt->execute([$sectionId]);
$result = $stmt->get_result();
$upload = $result->fetch_assoc();

if (!$upload) {
    echo json_encode(['success' => false, 'error' => 'Upload not found']);
    exit;
}

$filePath = __DIR__ . '/../assets/images/uploads/' . $upload['path'];

// Delete DB record
$stmt = $conn->prepare("DELETE FROM uploads WHERE section_id = ?");
$success = $stmt->execute([$sectionId]);

// Delete file from disk
$unlinkSuccess = null; // initialize

if ($success && file_exists($filePath)) {
    $unlinkSuccess = unlink($filePath);
}

$response = ['success' => $success];

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
