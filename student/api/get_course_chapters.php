<?php
// api/get_course_chapters.php
header("Content-Type: application/json");

include "../../includes/config.php";

/*
  Uses:
    - student_chapter_progress (chapter_percent)
    - student_course_progress  (course_percent)
    - module (mid, chapter_no, chapter_title, cm_id)
    - course_material (cm_id, launch_course_id, c_id)
    - launch_courses (id, course_name, course_code)
*/

$cm_id = isset($_GET['cm_id']) ? (int)$_GET['cm_id'] : 0;
$launch_c = isset($_GET['launch_c']) ? (int)$_GET['launch_c'] : 0;
$student_reg_no = $_SESSION['userid'] ?? null;

if (!$cm_id || !$launch_c || !$student_reg_no) {
    echo json_encode(["success" => false, "message" => "Missing parameters (cm_id, launch_c or session)"]);
    exit;
}

try {
    // --- 1) Course info ---
    $courseSql = "
        SELECT cm.c_id, cm.cm_id, lc.course_name, lc.course_code
        FROM course_material cm
        JOIN launch_courses lc ON cm.launch_course_id = lc.id
        WHERE cm.cm_id = ? AND cm.launch_course_id = ? LIMIT 1
    ";
    $courseStmt = $conn->prepare($courseSql);
    if (!$courseStmt) throw new Exception("Prepare failed (course query): " . $conn->error);
    if (!$courseStmt->bind_param("ii", $cm_id, $launch_c)) throw new Exception("Bind failed (course query): " . $courseStmt->error);
    if (!$courseStmt->execute()) throw new Exception("Execute failed (course query): " . $courseStmt->error);
    $course = $courseStmt->get_result()->fetch_assoc();
    $courseStmt->close();

    if (!$course) {
        echo json_encode(["success" => false, "message" => "Course not found for provided cm_id/launch_c"]);
        exit;
    }

    // --- 2) Chapters (module table) ---
    $chapSql = "
        SELECT mid, chapter_no, chapter_title
        FROM module
        WHERE cm_id = ?
        ORDER BY mid ASC
    ";
    $chapStmt = $conn->prepare($chapSql);
    if (!$chapStmt) throw new Exception("Prepare failed (chapters query): " . $conn->error);
    if (!$chapStmt->bind_param("i", $cm_id)) throw new Exception("Bind failed (chapters query): " . $chapStmt->error);
    if (!$chapStmt->execute()) throw new Exception("Execute failed (chapters query): " . $chapStmt->error);
    $chapters = $chapStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $chapStmt->close();

    // --- 3) Student per-chapter progress (new table student_chapter_progress, column chapter_percent) ---
    $chapProgSql = "
        SELECT chapter_id, chapter_percent
        FROM student_chapter_progress
        WHERE student_reg_no = ? AND cm_id = ?
    ";
    $chapProgStmt = $conn->prepare($chapProgSql);
    if (!$chapProgStmt) throw new Exception("Prepare failed (chapter progress): " . $conn->error);
    if (!$chapProgStmt->bind_param("si", $student_reg_no, $cm_id )) throw new Exception("Bind failed (chapter progress): " . $chapProgStmt->error);
    if (!$chapProgStmt->execute()) throw new Exception("Execute failed (chapter progress): " . $chapProgStmt->error);
    $studentChapterProgress = $chapProgStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $chapProgStmt->close();

    $progressMap = [];
    foreach ($studentChapterProgress as $p) {
        $progressMap[(int)$p['chapter_id']] = (int)$p['chapter_percent'];
    }

    // Attach progress to chapters and compute total
    $totalProgress = 0;
    foreach ($chapters as &$chap) {
        $chapId = (int)$chap['mid'];
        $chap['progress'] = $progressMap[$chapId] ?? 0;
        $totalProgress += $chap['progress'];
    }
    unset($chap); // break reference

    // --- 4) Overall course progress: try student_course_progress.course_percent first ---
    $overall = 0;
    $courseProgSql = "
        SELECT course_percent
        FROM student_course_progress
        WHERE student_reg_no = ? AND cm_id = ? LIMIT 1
    ";
    $courseProgStmt = $conn->prepare($courseProgSql);
    if (!$courseProgStmt) throw new Exception("Prepare failed (course progress): " . $conn->error);
    if (!$courseProgStmt->bind_param("si", $student_reg_no, $cm_id)) throw new Exception("Bind failed (course progress): " . $courseProgStmt->error);
    if (!$courseProgStmt->execute()) throw new Exception("Execute failed (course progress): " . $courseProgStmt->error);
    $courseProgRow = $courseProgStmt->get_result()->fetch_assoc();
    $courseProgStmt->close();

    if ($courseProgRow && isset($courseProgRow['course_percent'])) {
        $overall = (int)$courseProgRow['course_percent'];
    } else {
        // fallback: average of chapter percents
        $countCh = count($chapters);
        if ($countCh > 0) {
            $overall = (int) round($totalProgress / $countCh);
        } else {
            $overall = 0;
        }
    }

    echo json_encode([
        "success" => true,
        "course" => $course,
        "overall" => $overall,
        "chapters" => $chapters
    ]);
    exit;
} catch (Exception $e) {
    // Return JSON error with helpful message for debugging
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit;
}
