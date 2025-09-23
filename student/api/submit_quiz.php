<?php
include "../../includes/config.php";
header("Content-Type: application/json");


try {
    $input = json_decode(file_get_contents('php://input'), true);

    $student_reg_no = $_SESSION['userid'] ?? null;
    $cm_id          = $input['cm_id'] ?? null;
    $chapter_id     = $input['chapter_id'] ?? null;
    $answers        = $input['answers'] ?? [];

    if (!$student_reg_no || !$cm_id || !$chapter_id || empty($answers)) {
        throw new Exception("Missing parameters or answers");
    }

    // --- 1. Fetch correct answers with co_level ---
    $quizSql = "SELECT p_id, answer, co_level 
                FROM practise_question 
                WHERE cm_id=? AND module_id=?";
    $stmt = $conn->prepare($quizSql);
    if (!$stmt) throw new Exception("Prepare failed (quiz fetch): " . $conn->error);

    $stmt->bind_param("ii",$cm_id,$chapter_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $correctMap = [];
    $coMap      = [];
    while($row = $res->fetch_assoc()){
        $correctMap[$row['p_id']] = $row['answer'];
        $coMap[$row['p_id']]      = $row['co_level'];
    }
    $totalQuestions = count($correctMap);
    if ($totalQuestions === 0) throw new Exception("No questions found for this chapter");

    // --- 2. Insert student answers ---
    $stmtInsert = $conn->prepare("
        INSERT INTO practise_answer 
        (cm_id, student_reg_no, mid, co_level, qust_id, answer, last_inserted_on)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            answer=VALUES(answer), co_level=VALUES(co_level), last_inserted_on=NOW()
    ");
    if (!$stmtInsert) throw new Exception("Prepare failed (insert answers): ".$conn->error);

    $correctCount = 0;
    $answers_detail = [];

    foreach ($answers as $ans) {
        $qid    = (int)$ans['qust_id'];
        $answer = trim($ans['answer']);
        $co     = $coMap[$qid] ?? null;

        $stmtInsert->bind_param("isiiis", $cm_id, $student_reg_no, $chapter_id, $co, $qid, $answer);
        if (!$stmtInsert->execute()) {
            throw new Exception("Error inserting answer: ".$stmtInsert->error);
        }

        $isCorrect = isset($correctMap[$qid]) && $correctMap[$qid] === $answer;
        if ($isCorrect) $correctCount++;

        $answers_detail[] = [
            "question_id"     => $qid,
            "submitted_answer"=> $answer,
            "correct_answer"  => $correctMap[$qid] ?? null,
            "is_correct"      => $isCorrect
        ];
    }

    // --- 3. Pass/Fail ---
    $percentage = round(($correctCount / $totalQuestions) * 100, 2);
    $result = ($percentage >= 50) ? "Pass" : "Fail";

    // --- 4. Only update progress if Pass ---
    $chapter_percent = 0;
    $coursePercent   = 0;

    if ($result === "Pass") {
        // Chapter progress
        $chapter_percent = $percentage;
        $upd = $conn->prepare("
            INSERT INTO student_chapter_progress 
            (student_reg_no, cm_id, chapter_id, chapter_percent, unlocked, updated_at)
            VALUES (?, ?, ?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE 
                chapter_percent=VALUES(chapter_percent), 
                unlocked=1, 
                updated_at=NOW()
        ");
        $upd->bind_param("siii", $student_reg_no, $cm_id, $chapter_id, $chapter_percent);
        if (!$upd->execute()) throw new Exception("Error updating chapter progress: ".$upd->error);

        // Course progress
        $courseSql = "SELECT AVG(chapter_percent) as avg_percent 
                      FROM student_chapter_progress 
                      WHERE student_reg_no=? AND cm_id=?";
        $calc = $conn->prepare($courseSql);
        $calc->bind_param("si", $student_reg_no, $cm_id);
        $calc->execute();
        $avgRow = $calc->get_result()->fetch_assoc();
        $coursePercent = round($avgRow['avg_percent'] ?? 0);

        $upd2 = $conn->prepare("
            INSERT INTO student_course_progress 
            (student_reg_no, cm_id, course_percent, updated_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
                course_percent=VALUES(course_percent), 
                updated_at=NOW()
        ");
        $upd2->bind_param("sii", $student_reg_no, $cm_id, $coursePercent);
        if (!$upd2->execute()) throw new Exception("Error updating course progress: ".$upd2->error);

        // Unlock next chapter
        $nextChapterSql = "SELECT mid FROM module 
                           WHERE cm_id=? AND mid>? ORDER BY mid ASC LIMIT 1";
        $nstmt = $conn->prepare($nextChapterSql);
        $nstmt->bind_param("ss", $cm_id, $chapter_id);
        $nstmt->execute();
        $next = $nstmt->get_result()->fetch_assoc();

        if ($next) {
            $unlock = $conn->prepare("
                INSERT INTO student_chapter_progress (student_reg_no, cm_id, chapter_id, unlocked, updated_at)
                VALUES (?, ?, ?, 1, NOW())
                ON DUPLICATE KEY UPDATE unlocked=1, updated_at=NOW()
            ");
            $unlock->bind_param("sii", $student_reg_no, $cm_id, $next['chapter_id']);
            $unlock->execute();
        }
    }

    // --- 5. Final Response ---
    echo json_encode([
        "success"         => true,
        "correct_count"   => $correctCount,
        "total_questions" => $totalQuestions,
        "percentage"      => $percentage,
        "result"          => $result,
        "answers_detail"  => $answers_detail,
        "chapter_percent" => $chapter_percent,
        "course_percent"  => $coursePercent
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
