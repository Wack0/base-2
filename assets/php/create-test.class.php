<?php

class CreateTest extends Test {
  // Sets the custom property for the test.
  public function setCustom($custom)
  {
    $this->custom = $custom;
  }

  // Sets the teacher name property for the test.
  public function setTeacherName($name)
  {
    // Trim any whitespace.
    $name = trim($name);

    // As long as the name isn't blank and is also less than 70 chars long
    // set the teacher name property.
    if ( $name != "" && strlen($name) <= 70 ) {
      $this->teacherName = htmlentities($name,ENT_QUOTES); // XSS is bad, ok? :);
      return true;
    }
    else {
      return false;
    }
  }
  
  // Sets the message property for the test.
  public function setMessage($message)
  {
    // Remove any whitespace.
    $message = trim($message);

    // If the message is blank then set the message property to false
    // (the property is optional)
    if ( $message == "" ) {
      $this->message = false;
      return true;
    }
    // If it's not blank then check that it's less than 500 chars and 
    // set the property.
    elseif ( strlen($message) <= 500 ) {
      $this->message = htmlentities($message,ENT_QUOTES); // XSS is bad, ok? :)
      return true;
    }
    else {
      return false;
    }
  }

  // Set the difficulty property for the test.
  public function setDifficulty($difficulty)
  {
    // Remove whitespace.
    $difficulty = trim($difficulty);

    // If the difficulty is either easy, medium or hard then then it is valid.
    if ( in_array($difficulty, array("easy", "medium", "hard")) ) {
      $this->difficulty = $difficulty;
      return true;
    }
    else {
      return false;
    }
  }

  // Set the number of questions property for the test.
  public function setNumQuestions($num)
  {
    // Type cast the variable as an integer.
    $numQuestions = (int) $num;

    // Make sure the number of questions is between 1 and 100 and
    // set the property.
    if ( $numQuestions <= 100 && $numQuestions >= 1 ) {
      $this->numQuestions = $numQuestions;
      return true;
    }
    else {
      return false;
    }
  }

  // Generates a random 6 digit link as the test identifier.
  private function generateLink()
  {
    $randomLink = "";

    for ( $i = 0; $i < 6; $i++ ) {
      $randNum = rand(0, 9);

      $randomLink .= (string)$randNum;
    }

    return $randomLink;
  }

  // Inserts the test into the database.
  public function insertTest()
  {
    $db = $this->database;

    // Keep generating new links until a unique one is found.
    $link = $this->generateLink();

    if ( !$this->checkIfLinkExists($link) ) {
      $linkExists = false;
    }

    $attempts = 0;
    while ( $linkExists !== false && $attempts < 50 ) {
      $link = $this->generateLink();

      if ( !$this->checkIfLinkExists($link) ) {
        $linkExists = false;
      }

      $attempts++;
    }

    $this->link = $link;

    $difficulty = $this->difficulty;
    $numQuestions = $this->numQuestions;

    // If the test is custom then insert the teacher name and message properties as well.
    if ( $this->custom ) {
      $teacherName = $this->teacherName;
      // Because the message field is optional, if empty set it to null.
      $message = ($this->message === false ? null : $this->message);

      $sql = "INSERT INTO tbl_tests (
          test_teacher_name,
          test_message,
          test_difficulty,
          test_num_questions,
          test_custom,
          test_link)
        VALUES (
          :teacher_name,
          :message,
          :difficulty,
          :num_questions,
          1,
          :link)";
      
      $qry = $db->prepare($sql);
      $qry->execute(array(
        ":teacher_name" => $teacherName,
        ":message" => $message,
        ":difficulty" => $difficulty,
        ":num_questions" => $numQuestions,
        ":link" => $link
      ));
      
    }
    else {
      $sql = "INSERT INTO tbl_tests (
          test_difficulty,
          test_num_questions,
          test_link)
        VALUES (
          :difficulty,
          :num_questions,
          :link)";
      
      $qry = $db->prepare($sql);
      $qry->execute(array(
        ":difficulty" => $difficulty,
        ":num_questions" => $numQuestions,
        ":link" => $link
      ));
    }

    // After inserting the test, generate the questions for the test...
    $this->insertRandomQuestions($db->lastInsertId());
  }

  // Randomly generates questions for a given test and inserts them into the database.
  private function insertRandomQuestions($testID)
  {
    $questions = array();

    // Generate the correct number of questions using the Question class to generate
    // the questions.
    for ( $i = 0; $i < $this->numQuestions; $i++ ) {
      $question = new Question($this->difficulty);

      // Add the new questions and answers to the questions array.
      $questions[] = array(
        "Question" => $question->getQuestion(),
        "Answer" => $question->getAnswer(),
        "Number" => $i);
    }

    // Create the string for the sql statement that will insert all of the questions.
    $valueInsertString = "";
    foreach ($questions as $question) {
      $q = $question["Question"];
      $a = $question["Answer"];
      $n = $question["Number"];
      $valueInsertString .= "($testID, \"$q\", \"$a\", \"$n\"), ";
    }

    // Remove the unneeded comma from the end.
    $valueInsertString = substr($valueInsertString, 0, -2);

    $db = $this->database;
    $sql = "INSERT INTO tbl_questions (test_id, question_body, question_answer, question_number)
            VALUES $valueInsertString;";

    $qry = $db->prepare($sql);
    $qry->execute();
  }

  public function getLink()
  {
    return $this->link;
  }
}
