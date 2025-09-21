<?php
include "../../includes/config.php";

header("Content-Type: application/json");

$cm_id = $_GET['cm_id'] ?? null;
$launch_c = $_GET['launch_c'] ?? null;
$student_reg_no = $_SESSION['userid']; // pass from session or query

if (!$cm_id || !$launch_c || !$student_reg_no) {
    echo json_encode(["success" => false, "message" => "Missing parameters"]);
    exit;
}

// Fetch course info
$courseSql = "SELECT cm.c_id, cm.cm_id, lc.course_name, lc.course_code
              FROM course_material cm
              JOIN launch_courses lc ON cm.c_id = lc.c_id
              WHERE cm.cm_id = ? AND lc.id = ? LIMIT 1";

$stmt = $conn->prepare($courseSql);
$stmt->bind_param("ii", $cm_id, $launch_c);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
if (!$course) {
    echo json_encode(["success" => false, "message" => "Course not found"]);
    exit;
}

// Fetch chapters
$chapSql = "SELECT mid, chapter_no, chapter_title, materials, flipped_class, created_at
            FROM module
            WHERE cm_id = ?
            ORDER BY chapter_no DESC";

$stmt = $conn->prepare($chapSql);
$stmt->bind_param("i", $cm_id);
$stmt->execute();
$chapters = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch student progress
$progressSql = "SELECT chapter_id, progress_percent 
                FROM student_progress 
                WHERE student_reg_no = ? AND cm_id = ?";

$stmt = $conn->prepare($progressSql);
$stmt->bind_param("si", $student_reg_no, $cm_id);
$stmt->execute();
$studentProgress = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$progressMap = [];
foreach($studentProgress as $p){
    $progressMap[$p['chapter_id']] = $p['progress_percent'];
}

// Map progress to chapters
foreach($chapters as &$chap){
    $chap['progress'] = $progressMap[$chap['mid']] ?? 0;
}

echo json_encode([
    "success" => true,
    "course" => $course,
    "chapters" => $chapters
]);
