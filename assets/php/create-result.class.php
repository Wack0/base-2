<?php

class CreateResult extends Result {
  public function isCorrectAnswer($answer, $userAnswer)
  {
    $answer = $this->removeLeadingZeroes($answer);
    $userAnswer = $this->removeLeadingZeroes($userAnswer);

    if ( $answer == $userAnswer ) {
      return true;
    }
    else {
      return false;
    }
  }

  private function removeLeadingZeroes($string)
  {
    $string = trim($string);
    return preg_replace("/^0*/", "", $string);
  }

  public function insertResults($testID, $userID, $questions, $userAnswers)
  {
    $resultRows = array();

    for ( $i = 0; $i < count($questions); $i++ ) {
      $question = $questions[$i]["question_body"];
      $answer = $questions[$i]["question_answer"];
      $questionID = $questions[$i]["question_id"];
      $userAnswer = (trim($userAnswers[$i]) === "" ? null : trim($userAnswers[$i]));
      $userAnswer = htmlentities($userAnswer,ENT_QUOTES); // XSS is bad, ok? :)

      if ( $this->isCorrectAnswer($answer, $userAnswer) ) {
        $result = 1;
      }
      else {
        $result = 0;
      }

      $result = array(
        ":test_id" => $testID,
        ":user_id" => $userID,
        ":question_id" => $questionID,
        ":result" => $result,
        ":user_answer" => $userAnswer);

      $resultRows[] = $result;
    }

    $db = $this->database;
    $sql = "INSERT INTO tbl_results (test_id, user_id, question_id, result, user_answer)
    VALUES (:test_id, :user_id, :question_id, :result, :user_answer)";
    
    $qry = $db->prepare($sql);
    foreach ( $resultRows as $result ) {
      $qry->execute($result);
    }
  }
}
