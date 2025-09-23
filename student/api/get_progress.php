<?php
// api/get_progress.php
header('Content-Type: application/json');
include "../../includes/config.php";


try {
    $student = $_SESSION['reg_no'] ?? null;
    if (!$student) throw new Exception("Unauthorized");

    $cm_id = isset($_GET['cm_id']) ? (int)$_GET['cm_id'] : 0;
    if (!$cm_id) throw new Exception("Missing cm_id");

    // Get course percent
    $stmt = $conn->prepare("SELECT course_percent FROM student_course_progress WHERE student_reg_no = ? AND cm_id = ?");
    $stmt->bind_param("si", $student, $cm_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $course = $res->fetch_assoc();
    $coursePercent = $course['course_percent'] ?? 0;
    $stmt->close();

    // Get module list and student's chapter progress left-joined
    $stmt = $conn->prepare("
        SELECT m.chapter_id, m.chapter_no, m.chapter_title,
               scp.phase_material, scp.phase_video, scp.phase_quiz, scp.chapter_percent, scp.unlocked
        FROM module m
        LEFT JOIN student_chapter_progress scp
            ON scp.chapter_id = m.chapter_id AND scp.cm_id = m.cm_id AND scp.student_reg_no = ?
        WHERE m.cm_id = ? ORDER BY m.chapter_no ASC
    ");
    $stmt->bind_param("si", $student, $cm_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $chapters = [];
    while ($r = $res->fetch_assoc()) {
        // normalize nulls to zeros
        $r['phase_material'] = (int)($r['phase_material'] ?? 0);
        $r['phase_video'] = (int)($r['phase_video'] ?? 0);
        $r['phase_quiz'] = (int)($r['phase_quiz'] ?? 0);
        $r['chapter_percent'] = (int)($r['chapter_percent'] ?? 0);
        $r['unlocked'] = (int)($r['unlocked'] ?? 0);
        $chapters[] = $r;
    }
    $stmt->close();

    echo json_encode([
        'success' => true,
        'course_percent' => (int)$coursePercent,
        'chapters' => $chapters
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
