<?php
include '../../includes/config.php';
header('Content-Type: application/json');

$s_ass_id = $_POST['s_ass_id'] ?? null;
$value = $_POST['value'] ?? null;

if (!$s_ass_id || $value === null) {
    echo json_encode(['status' => 400, 'message' => 'Missing data']);
    exit;
}

// Update marks column (store either number or grade string)
$stmt = $conn->prepare("UPDATE student_assignment SET marks = ? WHERE s_ass_id = ?");
$stmt->bind_param("si", $value, $s_ass_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 200, 'message' => 'Updated successfully']);
} else {
    echo json_encode(['status' => 500, 'message' => 'Update failed']);
}
?>