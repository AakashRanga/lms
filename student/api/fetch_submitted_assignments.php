<?php
include '../../includes/config.php'; // DB connection


header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo json_encode([
        'status' => 401,
        'message' => 'User not logged in',
        'data' => []
    ]);
    exit;
}

$user_id   = $_SESSION['userid'];
$ass_id    = isset($_GET['ass_id']) ? intval($_GET['ass_id']) : 0;
$cm_id     = isset($_GET['cm_id']) ? intval($_GET['cm_id']) : 0;
$launch_id = isset($_GET['launch_c']) ? intval($_GET['launch_c']) : 0;

if ($ass_id <= 0 || $cm_id <= 0 || $launch_id <= 0) {
    echo json_encode([
        'status' => 400,
        'message' => 'Invalid parameters',
        'data' => []
    ]);
    exit;
}

// ✅ Fetch single assignment with student submission check
$stmt = $conn->prepare("
    SELECT 
        a.ass_id,
        a.title,
        a.notes,
        a.instruction,
        a.due_date,
        c.course_name,
        a.created_at,
        sa.s_ass_id  AS submission_id,
        sa.marks AS obtained_marks
    FROM assignment a
    INNER JOIN course c ON a.c_id = c.c_id
    LEFT JOIN student_assignment sa 
        ON sa.ass_id = a.ass_id AND sa.student_id = ?
    WHERE a.launch_id = ? AND a.c_id = ? AND a.ass_id = ?
    LIMIT 1
");

$stmt->bind_param("iiii", $user_id, $launch_id, $cm_id, $ass_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $status = $row['submission_id'] ? "Submitted" : "Pending";

    $assignment = [
        'ass_id'         => $row['ass_id'],
        'title'          => $row['title'],
        'course'         => $row['course_name'],
        'uploaded_files' => $row['notes'], // file path(s)
        'obtained_marks' => $row['obtained_marks'],
        'instruction'    => $row['instruction'],
        'due_date'       => date('Y-m-d', strtotime($row['due_date'])),
        'submission_date'=> date('Y-m-d', strtotime($row['created_at'])),
        'status'         => $status
    ];

    echo json_encode([
        'status' => 200,
        'message' => 'Assignment fetched successfully',
        'data' => [$assignment]
    ]);
} else {
    // Assignment not found
    echo json_encode([
        'status' => 404,
        'message' => 'Assignment not found',
        'data' => []
    ]);
}

$stmt->close();
$conn->close();
?>
