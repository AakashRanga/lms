<?php
include '../../includes/config.php';
header('Content-Type: application/json');

$assignment_id = $_POST['assignment_id'] ?? null;
$student_id = $_SESSION['userid'] ?? null; // logged-in student ID
// Use assignment title passed from frontend
$assignment_title = $_POST['title'] ?? '';
$stu_comments = $_POST['comments'];

if (!$assignment_id || !$student_id) {
    echo json_encode(['status' => 400, 'message' => 'Missing assignment or student']);
    exit;
}

// Handle file upload
$uploaded_files = [];
if (!empty($_FILES['files']['name'][0])) {
    $uploadDir = "../uploads/assignments/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['files']['name'] as $key => $name) {
        $tmpName = $_FILES['files']['tmp_name'][$key];
        $fileName = time() . "_" . basename($name);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            $uploaded_files[] = $filePath;
        }
    }
}

// Convert array to string for DB
$uploaded_files_str = implode(",", $uploaded_files);

// Fetch assignment details
$stmt = $conn->prepare("SELECT c_id, launch_id, instruction, marks 
                        FROM assignment WHERE ass_id = ?");
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();

if (!$assignment) {
    echo json_encode(['status' => 404, 'message' => 'Assignment not found']);
    exit;
}

// Fetch course title based on c_id
$titleStmt = $conn->prepare("SELECT course_name FROM course WHERE c_id = ?");
$titleStmt->bind_param("i", $assignment['c_id']);
$titleStmt->execute();
$titleRow = $titleStmt->get_result()->fetch_assoc();
$course_title = $titleRow['course_name'] ?? '';


// Insert into student_assignment
$stmt = $conn->prepare("INSERT INTO student_assignment 
    (ass_id, c_id, launch_id, student_id, title, instruction, notes, marks,comments, submission_date) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 0,?, NOW())");

$stmt->bind_param(
    "iiiissss",
    $assignment_id,
    $assignment['c_id'],
    $assignment['launch_id'],
    $student_id,
    $assignment_title,           // ✅ assignment title from frontend
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
            'assignment_title' => $assignment_title,
            'course_id' => $assignment['c_id'],
            'course_title' => $course_title,
            'launch_id' => $assignment['launch_id'],
            'student_id' => $student_id,
            'instruction' => $assignment['instruction'],
            'marks' => 0, // 👈 Always 0 at submission
            'submitted_files' => $uploaded_files, // array of file paths
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

