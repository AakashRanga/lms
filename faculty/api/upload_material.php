<?php
// api/upload_material.php
error_reporting(0);
header('Content-Type: application/json');

session_start();
include "../includes/config.php"; // adjust path

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Method Not Allowed', 405);
    }

    $faculty_id = $_SESSION['userid'] ?? null;
    if (!$faculty_id) {
        http_response_code(401);
        throw new Exception('Unauthorized: Faculty not logged in', 401);
    }

    // Required course identification (at least course_code or c_id)
    $course_name = trim($_POST['course_name'] ?? 'Ṇull');
    $course_code = trim($_POST['course_code'] ?? 'Ṇull');
    $c_id = trim($_POST['c_id'] ?? 'Ṇull');
    $launch_course_id = trim($_POST['launch_c_id'] ?? 'Ṇull');;

    // if (!$course_code && !$c_id) {
    //     http_response_code(400);
    //     throw new Exception('Missing course identifier (course_code or c_id required)', 400);
    // }

    // Collect module arrays
    $chapter_numbers = $_POST['chapter_number'] ?? [];
    $chapter_titles = $_POST['chapter_title'] ?? [];
    $module_co_levels = $_POST['co_level'] ?? []; // module level array length matches modules

    // Files arrays (they come as reading_material_files[] and video_material_files[])
    // Note: Because FormData may include placeholders (empty strings) for missing files, handle carefully
    $readingFiles = $_FILES['reading_material_files'] ?? null;
    $videoFiles = $_FILES['video_material_files'] ?? null;

    // Questions arrays — only valid questions were appended by frontend
    $questions_module_index = $_POST['questions_module_index'] ?? [];
    $question_texts = $_POST['question_text'] ?? [];
    $opt1 = $_POST['option1'] ?? [];
    $opt2 = $_POST['option2'] ?? [];
    $opt3 = $_POST['option3'] ?? [];
    $opt4 = $_POST['option4'] ?? [];
    $answers = $_POST['answer'] ?? [];
    $questions_co_level = $_POST['q_co_level'] ?? [];

    // Validate that at least one module exists
    if (count($chapter_numbers) === 0 && count($question_texts) === 0) {
        http_response_code(400);
        throw new Exception('No module or question data provided', 400);
    }

    // Start DB transaction
    $conn->begin_transaction();

    // Insert into course_material table
    $stmt = $conn->prepare("INSERT INTO course_material (course_code, c_id, faculty_id, launch_course_id, created_on) VALUES (?, ?, ?, ?, NOW())");
    if (!$stmt) throw new Exception('Prepare failed (course_material): ' . $conn->error);
    
    $stmt->bind_param("siis", $course_code, $c_id, $faculty_id, $launch_course_id);
    if (!$stmt->execute()) throw new Exception('Insert course_material failed: ' . $stmt->error);
    $cm_id = $stmt->insert_id;
    $stmt->close();

    // Prepare module insert
    $stmtModule = $conn->prepare("INSERT INTO module (cm_id, chapter_no, chapter_title, materials, flipped_class, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    if (!$stmtModule) throw new Exception('Prepare failed (module): ' . $conn->error);

    // Prepare practise_question insert
    $stmtQ = $conn->prepare("INSERT INTO practise_question (cm_id, question, option1, option2, option3, option4, answer, co_level, c_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    if (!$stmtQ) throw new Exception('Prepare failed (practise_question): ' . $conn->error);

    // Prepare file storage directories
    $baseDir = __DIR__ . '/../uploads/course_materials';
    if (!is_dir($baseDir)) mkdir($baseDir, 0755, true);
    $cmDir = $baseDir . '/cm_' . $cm_id;
    if (!is_dir($cmDir)) mkdir($cmDir, 0755, true);

    // Helper to handle uploaded file at index i in $_FILES structure
    function saveUploadedFileAtIndex($filesArray, $index, $destDir, $allowed = []) {
        if (!$filesArray || !isset($filesArray['name'][$index])) return null;
        // If placeholder was sent as empty string, name may be empty
        $name = $filesArray['name'][$index];
        if (!$name) return null;
        $tmp = $filesArray['tmp_name'][$index];
        if (!is_uploaded_file($tmp)) return null;
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        if (!empty($allowed) && !in_array(strtolower($ext), $allowed)) return null;
        $safe = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $name);
        $dest = rtrim($destDir, '/') . '/' . $safe;
        if (move_uploaded_file($tmp, $dest)) return $dest;
        return null;
    }

    // Loop modules and insert
    $moduleCount = max(count($chapter_numbers), count($chapter_titles));
    $readingIndex = 0; // index into reading_files
    $videoIndex = 0;

    for ($i = 0; $i < $moduleCount; $i++) {
        $chapNo = trim($chapter_numbers[$i] ?? '');
        $chapTitle = trim($chapter_titles[$i] ?? '');
        $m_co = trim($module_co_levels[$i] ?? '');

        // If module completely empty and no files/question mapping, skip
        // (frontend should avoid adding empty module but we double-check)
        $hasReading = ($readingFiles && isset($readingFiles['name'][$i]) && $readingFiles['name'][$i]);
        $hasVideo = ($videoFiles && isset($videoFiles['name'][$i]) && $videoFiles['name'][$i]);

        // Save files if present
        $materialPath = null;
        $videoPath = null;
        if ($hasReading) {
            $saved = saveUploadedFileAtIndex($readingFiles, $i, $cmDir, ['pdf']);
            if ($saved) $materialPath = $saved;
        }
        if ($hasVideo) {
            $saved = saveUploadedFileAtIndex($videoFiles, $i, $cmDir, ['mp4','mov','webm','mkv','avi']);
            if ($saved) $videoPath = $saved;
        }

        // If module has no data and no files and no questions belonging to this module index, skip
        $hasQuestionsForModule = in_array("$i", array_map('strval', $questions_module_index ?? []), true);
        if (!$chapNo && !$chapTitle && !$materialPath && !$videoPath && !$hasQuestionsForModule) {
            continue;
        }

        // Bind and execute module insert (materials and flipped_class store file path or NULL)
        $materialDbVal = $materialPath ? $materialPath : null;
        $videoDbVal = $videoPath ? $videoPath : null;
        $stmtModule->bind_param("issss", $cm_id, $chapNo, $chapTitle, $materialDbVal, $videoDbVal);
        if (!$stmtModule->execute()) throw new Exception('Insert module failed: ' . $stmtModule->error);
        $mid = $stmtModule->insert_id;

        // Insert questions that map to this module (iterate over question arrays)
        // We appended questions_module_index[] for each valid question on frontend
        if (!empty($question_texts)) {
            for ($q = 0; $q < count($question_texts); $q++) {
                // only insert if this question belongs to module index $i
                if (!isset($questions_module_index[$q])) continue;
                if ((string)$questions_module_index[$q] !== (string)$i) continue;

                $qtext = trim($question_texts[$q] ?? '');
                $qo1 = trim($opt1[$q] ?? '');
                $qo2 = trim($opt2[$q] ?? '');
                $qo3 = trim($opt3[$q] ?? '');
                $qo4 = trim($opt4[$q] ?? '');
                $qans = trim($answers[$q] ?? '');
                $qco = trim($questions_co_level[$q] ?? '');

                // Only insert if question text and answer present
                if (!$qtext || !$qans) continue;

                $stmtQ->bind_param("isssssssi", $cm_id, $qtext, $qo1, $qo2, $qo3, $qo4, $qans, $qco, $c_id);
                if (!$stmtQ->execute()) throw new Exception('Insert question failed: ' . $stmtQ->error);
            }
        }
    }

    // commit
    $stmtModule->close();
    $stmtQ->close();
    $conn->commit();

    echo json_encode(['status' => 200, 'message' => 'Course material uploaded successfully']);
    exit;

} catch (Exception $e) {
    // rollback if in transaction
    if ($conn && $conn->errno) {
        $conn->rollback();
    }
    $code = $e->getCode() ?: 500;
    http_response_code($code);
    echo json_encode(['status' => $code, 'message' => $e->getMessage()]);
    exit;
}
