<?php

class Test {
  protected $database;
  protected $custom;
  protected $teacherName;
  protected $message;
  protected $difficulty;
  protected $numQuestions;
  protected $link;
  protected $id;
  protected $test_date_created;

  public function __construct($database)
  {
    $this->database = $database;
  }

  // Checks if a specified link already exists in the db.
  protected function checkIfLinkExists($link)
  {
    $db = $this->database;
    $sql = "SELECT test_link FROM tbl_tests WHERE test_link = :l";
    $qry = $db->prepare($sql);
    $qry->execute(array(":l" => $link));

    // If no rows are found, it doesn't already exist.
    if ( $qry->rowCount() === 0 ) {
      return false;
    }
    else {
      return $link;
    }
  }

  public function isCustom()
  {
    if ( $this->custom ) {
      return true;
    }
    else {
      return false;
    }
  }

  public function getTeacherName()
  {
    return $this->teacherName;
  }

  public function getMessage()
  {
    $message = $this->message;

    if ( $message === null ) {
      $message = "None";
    }
    
    return $message;
  }

  public function getDifficulty($format)
  {
    $difficulty = $this->difficulty;

    if ( $format ) {
      switch ( $difficulty ) {
        case "e":
          $difficulty = "Easy";
          break;
        case "m":
          $difficulty = "Medium";
          break;
        case "h":
          $difficulty = "Hard";
          break;
      }
    }
    return $difficulty;
  }

  public function getNumQuestions()
  {
    return $this->numQuestions;
  }

  public function getDateCreated($format)
  {
    if ( $format ) {
      return date("d/m/y", strtotime($this->created));
    }
    else {
      return $this->created;
    }
  }

  public function getLink()
  {
    return $this->link;
  }

  public function getID()
  {
    return $this->id;
  }
}
