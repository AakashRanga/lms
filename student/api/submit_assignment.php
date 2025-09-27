<?php 
include '../../includes/config.php';
header('Content-Type: application/json');

$assignment_id = $_POST['assignment_id'] ?? null;
$student_id = $_SESSION['userid'] ?? null; 
$stu_comments = $_POST['comments'];

if (!$assignment_id || !$student_id) {
    echo json_encode(['status' => 400, 'message' => 'Missing assignment or student']);
    exit;
}

// ✅ Check if already submitted
$checkStmt = $conn->prepare("SELECT s_ass_id FROM student_assignment WHERE ass_id = ? AND student_id = ?");
$checkStmt->bind_param("ii", $assignment_id, $student_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode([
        'status' => 409,
        'message' => 'Assignment already submitted'
    ]);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// Handle file upload
$uploaded_files = [];
if (!empty($_FILES['files']['name'][0])) {
    $uploadDir = "../uploads/assignments/";
    $dbPathPrefix = "uploads/assignments/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['files']['name'] as $key => $name) {
        if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK && !empty($name)) {
            $tmpName = $_FILES['files']['tmp_name'][$key];
            $fileName = time() . "_" . basename($name);
            $filePath = $uploadDir . $fileName;
            $dbPath = $dbPathPrefix . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                if (!in_array($dbPath, $uploaded_files)) {
                    $uploaded_files[] = $dbPath;
                }
            }
        }
    }
}

$uploaded_files_str = implode(",", $uploaded_files);

// Fetch assignment details
$stmt = $conn->prepare("SELECT c_id, launch_id, instruction, title, marks 
                        FROM assignment WHERE ass_id = ?");
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();

if (!$assignment) {
    echo json_encode(['status' => 404, 'message' => 'Assignment not found']);
    exit;
}

// Fetch course title
$titleStmt = $conn->prepare("SELECT course_name FROM course WHERE c_id = ?");
$titleStmt->bind_param("i", $assignment['c_id']);
$titleStmt->execute();
$titleRow = $titleStmt->get_result()->fetch_assoc();
$course_title = $titleRow['course_name'] ?? '';

// Insert into student_assignment
$stmt = $conn->prepare("INSERT INTO student_assignment 
    (ass_id, c_id, launch_id, student_id, title, instruction, notes, marks, comments, submission_date) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, NOW())");

$stmt->bind_param(
    "iiiissss",
    $assignment_id,
    $assignment['c_id'],
    $assignment['launch_id'],
    $student_id,
    $assignment['title'],
    $assignment['instruction'],
    $uploaded_files_str,
    $stu_comments
);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 200,
        'message' => 'Assignment submitted successfully',
        'data' => [
            'assignment_id' => $assignment_id,
            'assignment_title' => $assignment['title'],
            'course_id' => $assignment['c_id'],
            'course_title' => $course_title,
            'launch_id' => $assignment['launch_id'],
            'student_id' => $student_id,
            'instruction' => $assignment['instruction'],
            'marks' => 0,
            'submitted_files' => $uploaded_files,
            'submission_date' => date("Y-m-d H:i:s"),
            'comments' => $stu_comments
        ]
    ]);
} else {
    echo json_encode([
        'status' => 500,
        'message' => 'Failed to submit assignment'
    ]);
}
