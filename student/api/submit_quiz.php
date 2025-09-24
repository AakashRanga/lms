<?php
include "../../includes/config.php";
header("Content-Type: application/json");

try {
    $input = json_decode(file_get_contents('php://input'), true);

    $student_reg_no = $_SESSION['userid'] ?? null;
    $cm_id = $input['cm_id'] ?? null;
    $chapter_id = $input['chapter_id'] ?? null;
    $answers = $input['answers'] ?? [];

    if (!$student_reg_no || !$cm_id || !$chapter_id || empty($answers)) {
        throw new Exception("Missing parameters or answers");
    }

    // --- 1. Fetch correct answers with co_level ---
    $quizSql = "SELECT p_id, answer, co_level 
                FROM practise_question 
                WHERE cm_id=? AND module_id=?";
    $stmt = $conn->prepare($quizSql);
    $stmt->bind_param("ii", $cm_id, $chapter_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $correctMap = [];
    $coMap = [];
    while ($row = $res->fetch_assoc()) {
        $correctMap[$row['p_id']] = $row['answer'];
        $coMap[$row['p_id']] = $row['co_level'];
    }
    $totalQuestions = count($correctMap);
    if ($totalQuestions === 0)
        throw new Exception("No questions found for this chapter");

    // --- 2. Insert/Update student answers ---
    $stmtInsert = $conn->prepare("
        INSERT INTO practise_answer 
        (cm_id, student_reg_no, mid, co_level, qust_id, answer, last_inserted_on)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            answer=VALUES(answer), co_level=VALUES(co_level), last_inserted_on=NOW()
    ");

    $correctCount = 0;
    $answers_detail = [];

    foreach ($answers as $ans) {
        $qid = (int) $ans['qust_id'];
        $answer = trim($ans['answer']);
        $co = $coMap[$qid] ?? null;

        $stmtInsert->bind_param("isiiis", $cm_id, $student_reg_no, $chapter_id, $co, $qid, $answer);
        $stmtInsert->execute();

        $isCorrect = isset($correctMap[$qid]) && $correctMap[$qid] === $answer;
        if ($isCorrect)
            $correctCount++;

        $answers_detail[] = [
            "question_id" => $qid,
            "submitted_answer" => $answer,
            "correct_answer" => $correctMap[$qid] ?? null,
            "is_correct" => $isCorrect
        ];
    }

    // --- 3. Pass/Fail & Chapter Percent ---
    $percentage = round(($correctCount / $totalQuestions) * 100, 2);
    $result = ($percentage >= 50) ? "Pass" : "Fail";
    $chapter_percent = ($result === "Pass") ? $percentage : 0;

    // --- 4. Update student chapter progress only ---
    $upd = $conn->prepare("
        UPDATE student_chapter_progress
        SET chapter_percent=?, updated_at=NOW()
        WHERE student_reg_no=? AND cm_id=? AND chapter_id=?
    ");
    $upd->bind_param("isis", $chapter_percent, $student_reg_no, $cm_id, $chapter_id);
    $upd->execute();
    $upd->close();

    // --- 5. Unlock next chapter ONLY if chapter_percent == 100 ---
    if ($chapter_percent == 100) {
        $nextChapterSql = "SELECT mid FROM module 
                           WHERE cm_id=? AND mid>? ORDER BY mid ASC LIMIT 1";
        $nstmt = $conn->prepare($nextChapterSql);
        $nstmt->bind_param("ii", $cm_id, $chapter_id);
        $nstmt->execute();
        $next = $nstmt->get_result()->fetch_assoc();
        $nstmt->close();

        if ($next) {
            $unlock = $conn->prepare("
                UPDATE student_chapter_progress
                SET unlocked=1, updated_at=NOW()
                WHERE student_reg_no=? AND cm_id=? AND chapter_id=?
            ");
            $unlock->bind_param("sii", $student_reg_no, $cm_id, $next['mid']);
            $unlock->execute();
            $unlock->close();
        }
    }

    // --- 6. Update course progress ---
    $courseSql = "SELECT AVG(chapter_percent) as avg_percent 
                  FROM student_chapter_progress 
                  WHERE student_reg_no=? AND cm_id=?";
    $calc = $conn->prepare($courseSql);
    $calc->bind_param("si", $student_reg_no, $cm_id);
    $calc->execute();
    $avgRow = $calc->get_result()->fetch_assoc();
    $coursePercent = round($avgRow['avg_percent'] ?? 0);
    $calc->close();

    $updCourse = $conn->prepare("
        UPDATE student_course_progress
        SET course_percent=?, updated_at=NOW()
        WHERE student_reg_no=? AND cm_id=?
    ");
    $updCourse->bind_param("iis", $coursePercent, $student_reg_no, $cm_id);
    $updCourse->execute();
    $updCourse->close();

    // --- 7. Response ---
    echo json_encode([
        "success" => true,
        "correct_count" => $correctCount,
        "total_questions" => $totalQuestions,
        "percentage" => $percentage,
        "result" => $result,
        "answers_detail" => $answers_detail,
        "chapter_percent" => $chapter_percent,
        "course_percent" => $coursePercent
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
