<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include '../../includes/config.php'; // DB connection



try {
    $faculty_id = $_SESSION['userid'] ?? null;
    $launch_course_id = $_GET['launch_c_id'] ?? null;

    if (empty($faculty_id)) {
        echo json_encode(["status" => "error", "message" => "Missing faculty_id"]);
        exit;
    }

    if (empty($launch_course_id)) {
        echo json_encode(["status" => "error", "message" => "Missing launch_c_id"]);
        exit;
    }

    // Fetch the specific launched course for this faculty
    $sql = "SELECT id AS launch_id, course_name, course_code, c_id, status, created_at 
            FROM launch_courses 
            WHERE faculty_id = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $faculty_id, $launch_course_id);
    $stmt->execute();
    $launch_result = $stmt->get_result();

    $data = [];

    while ($launch = $launch_result->fetch_assoc()) {
        $launch_id = $launch['launch_id'];
        $course_id = $launch['c_id'];

        // Fetch course materials for this specific course launch
        $mat_sql = "SELECT cm_id, course_code, created_on
                    FROM course_material
                    WHERE faculty_id = ? AND launch_course_id = ?";
        $mat_stmt = $conn->prepare($mat_sql);
        $mat_stmt->bind_param("ii", $faculty_id, $launch_id);
        $mat_stmt->execute();
        $mat_result = $mat_stmt->get_result();

        $materials = [];

        while ($mat = $mat_result->fetch_assoc()) {
            $cm_id = $mat['cm_id'];

            // Fetch modules (chapters)
            $mod_sql = "SELECT mid, chapter_no, chapter_title, materials, flipped_class, created_at
                        FROM module
                        WHERE cm_id = ?";
            $mod_stmt = $conn->prepare($mod_sql);
            $mod_stmt->bind_param("i", $cm_id);
            $mod_stmt->execute();
            $mod_result = $mod_stmt->get_result();

            $modules = [];
            while ($mod = $mod_result->fetch_assoc()) {
                $modules[] = $mod;
            }

            $mat['modules'] = $modules;
            $materials[] = $mat;
        }

        $launch['materials'] = $materials;
        $data[] = $launch;
    }

    echo json_encode(["status" => "success", "data" => $data], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
