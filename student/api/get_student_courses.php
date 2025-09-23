<?php
// api/get_student_courses.php
header("Content-Type: application/json");

include "../../includes/config.php";

$studentRegNo = $_SESSION["userid"] ?? null;
if (!$studentRegNo) {
    echo json_encode(["success" => false, "message" => "Student not logged in"]);
    exit;
}

try {
    // main query: get student's approved launches + related course material
    $sql = "
        SELECT sca.stu_ap_id, sca.launch_c_id, sca.student_name, sca.student_reg_no,
               lc.id AS launch_id, lc.course_name, lc.course_code, lc.faculty_id, lc.c_id, lc.faculty_name,
               cm.cm_id, cm.thumbnail
        FROM student_course_approval sca
        INNER JOIN launch_courses lc ON sca.launch_c_id = lc.id
        INNER JOIN course_material cm 
                ON cm.c_id = lc.c_id 
               AND cm.faculty_id = lc.faculty_id 
               AND cm.launch_course_id = lc.id
        WHERE sca.student_reg_no = ? AND sca.status = 'approved'
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Prepare failed (main query): " . $conn->error);
    $stmt->bind_param("s", $studentRegNo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare statements we will reuse inside loop (check prepares)
    $progressSql = "SELECT course_percent FROM student_course_progress WHERE student_reg_no = ? AND cm_id = ? AND launch_course_id = ? LIMIT 1";
    $progressStmt = $conn->prepare($progressSql);
    if (!$progressStmt) throw new Exception("Prepare failed (progress lookup): " . $conn->error);

    $totalChaptersSql = "SELECT COUNT(*) AS total FROM module WHERE cm_id = ?";
    $chapStmt = $conn->prepare($totalChaptersSql);
    if (!$chapStmt) throw new Exception("Prepare failed (chapter count): " . $conn->error);

    $sumChapterPercentSql = "SELECT COALESCE(SUM(chapter_percent),0) AS sumperc FROM student_chapter_progress WHERE student_reg_no = ? AND cm_id = ? AND launch_course_id = ?";
    $sumStmt = $conn->prepare($sumChapterPercentSql);
    if (!$sumStmt) throw new Exception("Prepare failed (sum chapter percent): " . $conn->error);

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        // normalize ids
        $cm_id = (int)($row['cm_id'] ?? 0);
        $launchId = (int)($row['launch_id'] ?? 0);

        $progress = 0;

        // Try fast path: stored course progress
        $progressStmt->bind_param("sii", $studentRegNo, $cm_id, $launchId);
        if (!$progressStmt->execute()) {
            // if execution fails, throw for clarity
            throw new Exception("Failed to execute progress lookup: " . $progressStmt->error);
        }
        $pRes = $progressStmt->get_result();
        if ($pRes && $pRes->num_rows > 0) {
            $pRow = $pRes->fetch_assoc();
            $progress = (int)($pRow['course_percent'] ?? 0);
        } else {
            // fallback: compute from chapter-level data
            $chapStmt->bind_param("i", $cm_id);
            if (!$chapStmt->execute()) throw new Exception("Failed to execute chapter count: " . $chapStmt->error);
            $total = (int)($chapStmt->get_result()->fetch_assoc()['total'] ?? 0);

            if ($total > 0) {
                $sumStmt->bind_param("sis", $studentRegNo, $cm_id, $launchId);
                if (!$sumStmt->execute()) throw new Exception("Failed to execute chapter sum: " . $sumStmt->error);
                $sumPerc = (int)($sumStmt->get_result()->fetch_assoc()['sumperc'] ?? 0);

                // average percent across chapters
                $progress = (int) round($sumPerc / $total);
            } else {
                $progress = 0;
            }
        }

        // Thumbnail URL (ensure THUMBNAIL_URL is defined in config)
        $thumbnail_url = (defined('THUMBNAIL_URL') && !empty($row['thumbnail'])) ? rtrim(THUMBNAIL_URL, '/') . '/' . ltrim($row['thumbnail'], '/') : ($row['thumbnail'] ?? '');

        $row['progress'] = $progress;
        $row['thumbnail_url'] = $thumbnail_url;

        $courses[] = $row;
    }

    // close statements
    $progressStmt->close();
    $chapStmt->close();
    $sumStmt->close();
    $stmt->close();

    echo json_encode(["success" => true, "data" => $courses]);

} catch (Exception $e) {
    // give useful error to aid debugging (you can remove the message text on production)
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
