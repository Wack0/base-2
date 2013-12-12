<?php

class ViewTest extends Test {
  public function setTestLink($link)
  {
    if ( $this->checkIfLinkExists($link) ) {
      $this->link = $link;
      return true;
    }
    else {
      return false;
    }
  }

  public function selectTest($id = false)
  {
    $db = $this->database;

    if ( !$id ) {
      $link = $this->link;
      $sql = "SELECT * FROM tbl_tests WHERE test_link = :link";
      $qry = $db->prepare($sql);
      $qry->execute(array(":link" => $link));
    }
    else {
      $sql = "SELECT * FROM tbl_tests WHERE test_id = :id";
      $qry = $db->prepare($sql);
      $qry->execute(array(":id" => $id));
    }
    
    if ( $qry->rowCount() === 0 ) {
      return false;
    }
    else {
      foreach ($qry as $row) {
        $this->id = $row["test_id"];
        $this->teacherName = $row["test_teacher_name"];
        $this->message = $row["test_message"];
        $this->difficulty = $row["test_difficulty"];
        $this->numQuestions = $row["test_num_questions"];
        $this->custom = $row["test_custom"];
        $this->created = $row["test_date_created"];
        $this->link = $row["test_link"];
      }
      return true;
    }
  }

  public function selectQuestions()
  {
    $id = $this->id;
    $db = $this->database;
    $sql = "SELECT * FROM  tbl_questions WHERE test_id = :id ORDER BY question_number ASC";
    $qry = $db->prepare($sql);
    $qry->execute(array(":id" => $id));
    $questions = array();
    foreach ($qry as $question) {
      $questions[] = $question;
    }

    return $questions;
  }
}
