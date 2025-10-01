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

    // 1. Get correct answers
    $stmt = $conn->prepare("SELECT p_id,answer,co_level FROM practise_question WHERE cm_id=? AND module_id=?");
    $stmt->bind_param("ii",$cm_id,$chapter_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $correctMap=[]; $coMap=[];
    while($r=$res->fetch_assoc()){ $correctMap[$r['p_id']]=$r['answer']; $coMap[$r['p_id']]=$r['co_level']; }
    $stmt->close();
    $totalQuestions = count($correctMap);
    if($totalQuestions==0) throw new Exception("No questions found");

    // 2. Save answers
    $ins = $conn->prepare("INSERT INTO practise_answer (cm_id,student_reg_no,mid,co_level,qust_id,answer,last_inserted_on)
                           VALUES (?,?,?,?,?,?,NOW())
                           ON DUPLICATE KEY UPDATE answer=VALUES(answer),co_level=VALUES(co_level),last_inserted_on=NOW()");
    $correctCount=0; $answers_detail=[];
    foreach($answers as $ans){
        $qid=(int)$ans['qust_id']; $answer=trim($ans['answer']); $co=$coMap[$qid]??null;
        $ins->bind_param("isiiis",$cm_id,$student_reg_no,$chapter_id,$co,$qid,$answer);
        $ins->execute();
        $isCorrect=(isset($correctMap[$qid]) && $correctMap[$qid]===$answer);
        if($isCorrect) $correctCount++;
        $answers_detail[]=["question_id"=>$qid,"submitted_answer"=>$answer,"correct_answer"=>$correctMap[$qid]??null,"is_correct"=>$isCorrect];
    }
    $ins->close();

    // 3. Result
    $percentage=round(($correctCount/$totalQuestions)*100,2);
    $result=($percentage>=50)?"Pass":"Fail";
    $quizScore=($result==="Pass")?$percentage:0;

    // 4. Update phase_quiz + chapter_percent
    $conn->query("UPDATE student_chapter_progress 
                  SET phase_quiz=$quizScore, 
                      chapter_percent=ROUND((phase_material*0.25 + phase_video*0.25 + $quizScore*0.5)),
                      updated_at=NOW()
                  WHERE student_reg_no='$student_reg_no' AND cm_id=$cm_id AND chapter_id=$chapter_id");

    // 5. Unlock next chapter if sequential and chapter=100
    $cpRes=$conn->query("SELECT chapter_percent FROM student_chapter_progress WHERE student_reg_no='$student_reg_no' AND cm_id=$cm_id AND chapter_id=$chapter_id");
    $chapter_percent=intval($cpRes->fetch_assoc()['chapter_percent']);
    $learningRes=$conn->query("SELECT learning_type FROM course_material WHERE cm_id=$cm_id");
    $learning_type=strtolower($learningRes->fetch_assoc()['learning_type']??'flexible');
    if($learning_type==='sequential' && $chapter_percent==100){
        $nx=$conn->query("SELECT mid FROM module WHERE cm_id=$cm_id AND mid>$chapter_id ORDER BY mid ASC LIMIT 1");
        if($nxRow=$nx->fetch_assoc()){
            $next=$nxRow['mid'];
            $conn->query("INSERT INTO student_chapter_progress (student_reg_no,cm_id,launch_course_id,chapter_id,phase_material,phase_video,phase_quiz,chapter_percent,unlocked,started_at,updated_at)
                          VALUES('$student_reg_no',$cm_id,NULL,$next,0,0,0,0,1,NOW(),NOW())
                          ON DUPLICATE KEY UPDATE unlocked=1,updated_at=NOW()");
        }
    }

    // ✅ 6. Correct course progress
    $sql="SELECT (SUM(COALESCE(scp.chapter_percent,0)) / COUNT(m.mid)) AS course_percent
          FROM module m
          LEFT JOIN student_chapter_progress scp
            ON scp.cm_id=m.cm_id AND scp.chapter_id=m.mid AND scp.student_reg_no='$student_reg_no'
          WHERE m.cm_id=$cm_id";
    $course_percent=round($conn->query($sql)->fetch_assoc()['course_percent']);

    $conn->query("INSERT INTO student_course_progress (student_reg_no,cm_id,launch_course_id,course_percent,course_started_at,updated_at)
                  VALUES('$student_reg_no',$cm_id,NULL,$course_percent,NOW(),NOW())
                  ON DUPLICATE KEY UPDATE course_percent=$course_percent,updated_at=NOW()");

    echo json_encode([
        "success"=>true,
        "correct_count"=>$correctCount,
        "total_questions"=>$totalQuestions,
        "percentage"=>$percentage,
        "result"=>$result,
        "answers_detail"=>$answers_detail,
        "chapter_percent"=>$chapter_percent,
        "course_percent"=>$course_percent
    ]);

} catch(Exception $e){
    echo json_encode(["success"=>false,"message"=>$e->getMessage()]);
}
