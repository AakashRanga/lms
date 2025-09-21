<?php
header("Content-Type: application/json");
include "../../includes/config.php";

$studentRegNo =  $_SESSION["userid"];

if (!$studentRegNo) {
    echo json_encode(["success" => false, "message" => "Student not logged in"]);
    exit;
}

try {
    $sql = "SELECT sca.stu_ap_id, sca.launch_c_id, sca.student_name, sca.student_reg_no,
                   lc.id, lc.course_name, lc.course_code, lc.faculty_id, lc.c_id, lc.faculty_name,
                   cm.cm_id, cm.thumbnail
            FROM student_course_approval sca
            INNER JOIN launch_courses lc ON sca.launch_c_id = lc.id
            INNER JOIN course_material cm 
                    ON cm.c_id = lc.c_id 
                   AND cm.faculty_id = lc.faculty_id 
                   AND cm.launch_course_id = lc.id
            WHERE sca.student_reg_no = ? AND sca.status = 'approved'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentRegNo);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        // fetch progress later (for now keep 0)
        $row['progress'] = 0;
        $thumbnail_url = THUMBNAIL_URL . '/' . $row['thumbnail'];
        $row['thumbnail_url'] = $thumbnail_url;
        $courses[] = $row;
    }

    echo json_encode(["success" => true, "data" => $courses]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
