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
    // main query: get student's approved courses + related material
    $sql = "
        SELECT sca.stu_ap_id, sca.launch_c_id, sca.student_name, sca.student_reg_no,
               lc.id AS launch_id, lc.course_name, lc.course_code, lc.faculty_id, lc.c_id, lc.faculty_name,
               cm.cm_id, cm.thumbnail
        FROM student_course_approval sca
        INNER JOIN launch_courses lc ON sca.launch_c_id = lc.id
        INNER JOIN course_material cm 
                ON cm.c_id = lc.c_id 
               AND cm.faculty_id = lc.faculty_id 
        WHERE sca.student_reg_no = ? AND sca.status = 'approved'
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Prepare failed (main query): " . $conn->error);
    $stmt->bind_param("s", $studentRegNo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Progress lookup (course-level, no launch_id)
    $progressSql = "SELECT course_percent 
                    FROM student_course_progress 
                    WHERE student_reg_no = ? AND cm_id = ? 
                    LIMIT 1";
    $progressStmt = $conn->prepare($progressSql);
    if (!$progressStmt) throw new Exception("Prepare failed (progress lookup): " . $conn->error);

    // Chapter count
    $totalChaptersSql = "SELECT COUNT(*) AS total FROM module WHERE cm_id = ?";
    $chapStmt = $conn->prepare($totalChaptersSql);
    if (!$chapStmt) throw new Exception("Prepare failed (chapter count): " . $conn->error);

    // Sum of chapter progress (no launch_id)
    $sumChapterPercentSql = "SELECT COALESCE(SUM(chapter_percent),0) AS sumperc 
                             FROM student_chapter_progress 
                             WHERE student_reg_no = ? AND cm_id = ?";
    $sumStmt = $conn->prepare($sumChapterPercentSql);
    if (!$sumStmt) throw new Exception("Prepare failed (sum chapter percent): " . $conn->error);

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $cm_id = (int)($row['cm_id'] ?? 0);
        $progress = 0;

        // Try stored course progress first
        $progressStmt->bind_param("si", $studentRegNo, $cm_id);
        if (!$progressStmt->execute()) {
            throw new Exception("Failed to execute progress lookup: " . $progressStmt->error);
        }
        $pRes = $progressStmt->get_result();
        if ($pRes && $pRes->num_rows > 0) {
            $pRow = $pRes->fetch_assoc();
            $progress = (int)($pRow['course_percent'] ?? 0);
        } else {
            // fallback → compute from chapters
            $chapStmt->bind_param("i", $cm_id);
            if (!$chapStmt->execute()) throw new Exception("Failed to execute chapter count: " . $chapStmt->error);
            $total = (int)($chapStmt->get_result()->fetch_assoc()['total'] ?? 0);

            if ($total > 0) {
                $sumStmt->bind_param("si", $studentRegNo, $cm_id);
                if (!$sumStmt->execute()) throw new Exception("Failed to execute chapter sum: " . $sumStmt->error);
                $sumPerc = (int)($sumStmt->get_result()->fetch_assoc()['sumperc'] ?? 0);

                $progress = (int) round($sumPerc / $total);
            } else {
                $progress = 0;
            }
        }

        // Thumbnail URL
        $thumbnail_url = (defined('THUMBNAIL_URL') && !empty($row['thumbnail']))
            ? rtrim(THUMBNAIL_URL, '/') . '/' . ltrim($row['thumbnail'], '/')
            : ($row['thumbnail'] ?? '');

        $row['progress'] = $progress;
        $row['thumbnail_url'] = $thumbnail_url;

        // 🚀 Keep launch_id in response (but not used in progress calculation)
        $courses[] = $row;
    }

    // cleanup
    $progressStmt->close();
    $chapStmt->close();
    $sumStmt->close();
    $stmt->close();

    echo json_encode(["success" => true, "data" => $courses]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
