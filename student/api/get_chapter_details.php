<?php
include "../../includes/config.php";
header("Content-Type: application/json");

$chapter_id = $_GET['chapter_id'] ?? null;
$cm_id = $_GET['cm_id'] ?? null;
$student_reg_no = $_SESSION['userid'];

if (!$chapter_id || !$cm_id || !$student_reg_no) {
    echo json_encode(["success"=>false, "message"=>"Missing parameters"]);
    exit;
}

// Fetch chapter info
$chapterSql = "SELECT m.mid, m.chapter_no, m.chapter_title, m.materials, m.flipped_class,
                      cm.cm_id, cm.course_code, lc.course_name
               FROM module m
               JOIN course_material cm ON m.cm_id = cm.cm_id
               JOIN launch_courses lc ON cm.c_id = lc.c_id
               WHERE m.mid=? AND cm.cm_id=? LIMIT 1";

$stmt = $conn->prepare($chapterSql);
$stmt->bind_param("ii", $chapter_id, $cm_id);
$stmt->execute();
$chapter = $stmt->get_result()->fetch_assoc();

if (!$chapter) {
    echo json_encode(["success"=>false, "message"=>"Chapter not found"]);
    exit;
}

// Fetch student progress for this chapter
$progressSql = "SELECT progress_percent FROM student_progress 
                WHERE student_reg_no=? AND cm_id=? AND chapter_id=? LIMIT 1";
$stmt = $conn->prepare($progressSql);
$stmt->bind_param("sii", $student_reg_no, $cm_id, $chapter_id);
$stmt->execute();
$progress = $stmt->get_result()->fetch_assoc();
$chapter['progress'] = $progress['progress_percent'] ?? 0;

// Fetch quiz questions
$quizSql = "SELECT p_id, question, option1, option2, option3, option4, answer
            FROM practise_question
            WHERE cm_id=? AND module_id=?";

$stmt = $conn->prepare($quizSql);
$stmt->bind_param("ii", $cm_id, $chapter_id);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "success"=>true,
    "chapter"=>$chapter,
    "quiz"=>$questions
]);
