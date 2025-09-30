<?php
header('Content-Type: application/json');
include "../../includes/config.php";

$facultyid = $_SESSION['userid'];

$sql = "SELECT 
    sca.stu_ap_id AS approval_id,
    sca.student_name,
    sca.student_reg_no,
    l.reg_no AS login_register_number, -- ✅ from login table
    sca.slot,
    sca.status,
    sca.created_at,
    lc.course_name,
    lc.course_code,
    lc.seat_allotment,
    lc.course_type,
    lc.faculty_name
FROM student_course_approval sca
INNER JOIN launch_courses lc 
    ON sca.launch_c_id = lc.id
INNER JOIN lms_login l 
    ON sca.student_reg_no = l.u_id   -- ✅ join with login
WHERE lc.faculty_id = $facultyid
ORDER BY sca.created_at ASC;;
";
$result = $conn->query($sql);

$courses = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

echo json_encode($courses);
$conn->close();
