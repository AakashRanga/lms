<?php 
// api/upload_material.php
header("Content-Type: application/json");

include "../../includes/config.php";

// ---------- Helper: safe filename with auto-rename ----------
function getSafeFileName($uploadDir, $originalName) {
    $safeName = preg_replace("/[^A-Za-z0-9_\.-]/", "_", strtolower($originalName));

    $pathInfo = pathinfo($safeName);
    $base = $pathInfo['filename'];
    $ext  = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';

    $counter = 1;
    $finalName = $safeName;

    while (file_exists($uploadDir . $finalName)) {
        $finalName = $base . "(" . $counter . ")" . $ext;
        $counter++;
    }

    return $finalName;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    $faculty_id = $_SESSION['userid'] ?? null;
    if (!$faculty_id) {
        throw new Exception("Unauthorized access. Please login again.");
    }

    // Collect course info
    $course_code      = $_POST['course_code'] ?? '';
    $c_id             = $_POST['c_id'] ?? '';
    $launch_course_id = $_POST['launch_c_id'] ?? '';
    $learning_type    = $_POST['learning_type'] ?? '';
    $thumbnail        = $_FILES['thumbnail'] ?? null;

    if (!$course_code || !$c_id || !$launch_course_id || !$learning_type) {
        throw new Exception("Missing course details");
    }

    // ---------- Handle thumbnail upload ----------
    $thumbnailPath = null;
    if ($thumbnail && $thumbnail['tmp_name']) {
        $uploadDir = __DIR__ . "/../uploads/course_materials/thumbnail/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $finalName  = getSafeFileName($uploadDir, $thumbnail['name']);
        $targetPath = $uploadDir . $finalName;

        if (!move_uploaded_file($thumbnail['tmp_name'], $targetPath)) {
            throw new Exception("Failed to upload thumbnail");
        }

        $thumbnailPath = $finalName;
    }

    // ---------- Start DB transaction ----------
    $conn->begin_transaction();

    // ✅ Check if course_material already exists
    $checkStmt = $conn->prepare("
        SELECT cm_id 
        FROM course_material 
        WHERE course_code = ? AND faculty_id = ? AND launch_course_id = ?
    ");
    $checkStmt->bind_param("sis", $course_code, $faculty_id, $launch_course_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->bind_result($cm_id);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($thumbnailPath || $learning_type) {
            $updateStmt = $conn->prepare("
                UPDATE course_material
                SET thumbnail = COALESCE(?, thumbnail),
                    learning_type = COALESCE(?, learning_type)
                WHERE cm_id = ?
            ");
            $updateStmt->bind_param("ssi", $thumbnailPath, $learning_type, $cm_id);
            if (!$updateStmt->execute()) throw new Exception("Failed to update course_material: " . $updateStmt->error);
            $updateStmt->close();
        }
    } else {
        $checkStmt->close();
        $stmt = $conn->prepare("
            INSERT INTO course_material (course_code, c_id, faculty_id, launch_course_id, thumbnail, learning_type, created_on) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("siisss", $course_code, $c_id, $faculty_id, $launch_course_id, $thumbnailPath, $learning_type);
        if (!$stmt->execute()) throw new Exception("Failed to insert course_material: " . $stmt->error);
        $cm_id = $stmt->insert_id;
        $stmt->close();
    }

    // ✅ Prepare insert statements
    $stmtModule = $conn->prepare("
        INSERT INTO module (cm_id, chapter_no, chapter_title, materials, flipped_class, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $stmtQ = $conn->prepare("
        INSERT INTO practise_question 
        (cm_id, module_id, question, option1, option2, option3, option4, answer, co_level, c_id, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    foreach ($_POST['chapter_number'] as $i => $chapNo) {
        $chapTitle = $_POST['chapter_title'][$i] ?? '';

        $readingPath = $videoPath = null;

        // ---------- Reading Material ----------
        if (!empty($_FILES['reading_material']['name'][$i])) {
            $uploadDir = __DIR__ . "/../uploads/course_materials/pdf/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $finalPdf = getSafeFileName($uploadDir, $_FILES['reading_material']['name'][$i]);
            $readingPath = "uploads/course_materials/pdf/" . $finalPdf;

            move_uploaded_file($_FILES['reading_material']['tmp_name'][$i], $uploadDir . $finalPdf);
        }

        // ---------- Video Material ----------
        if (!empty($_FILES['video_material']['name'][$i])) {
            $uploadDir = __DIR__ . "/../uploads/course_materials/video/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $finalVideo = getSafeFileName($uploadDir, $_FILES['video_material']['name'][$i]);
            $videoPath = "uploads/course_materials/video/" . $finalVideo;

            move_uploaded_file($_FILES['video_material']['tmp_name'][$i], $uploadDir . $finalVideo);
        }

        // Insert module
        $stmtModule->bind_param("issss", $cm_id, $chapNo, $chapTitle, $readingPath, $videoPath);
        if (!$stmtModule->execute()) throw new Exception("Failed to insert module: " . $stmtModule->error);
        $module_id = $stmtModule->insert_id;

        // Insert related questions
        if (!empty($_POST['question_text'][$i])) {
            foreach ($_POST['question_text'][$i] as $qIndex => $qText) {
                if (empty($qText)) continue;

                $o1  = $_POST['option_a'][$i][$qIndex] ?? '';
                $o2  = $_POST['option_b'][$i][$qIndex] ?? '';
                $o3  = $_POST['option_c'][$i][$qIndex] ?? '';
                $o4  = $_POST['option_d'][$i][$qIndex] ?? '';
                $ans = $_POST['correct_answer_' . $i . '_' . ($qIndex + 1)] ?? '';
                $co  = $_POST['co_level'][$i][$qIndex] ?? '';

                $stmtQ->bind_param(
                    "issssssssi",
                    $cm_id,
                    $module_id,
                    $qText,
                    $o1,
                    $o2,
                    $o3,
                    $o4,
                    $ans,
                    $co,
                    $c_id
                );

                if (!$stmtQ->execute()) throw new Exception("Failed to insert question: " . $stmtQ->error);
            }
        }
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(["success" => true, "message" => "Course material uploaded successfully!", "cm_id" => $cm_id]);
} catch (Exception $e) {
    if ($conn->errno) {
        $conn->rollback();
    }
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
