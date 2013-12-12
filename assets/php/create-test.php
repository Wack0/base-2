<?php

include "functions.php";

// Check that the difficulty and number of questions POST variables are set.
if ( issetCheck(array("difficulty", "number-of-questions")) ) {

  // If the teacher name and message variables are set then it can be assumed 
  // the test is custom.
  if ( issetCheck(array("teacher-name", "message")) ) {
    $custom = true;
  }
  else {
    $custom = false;
  }

  // Import the needed classes.
  include "database.class.php";
  include "test.class.php";
  include "create-test.class.php";
  include "question.class.php";
  include "binary.class.php";

  // Create a new database connection and create a new test object.
  $database = new Database();
  $Test = new CreateTest($database->getDatabase());

  $invalidFields = array();

  // Set each of the properties and fill the invalidFields array with any fields
  // that are invalid.
  if ( !$Test->setDifficulty($_POST["difficulty"]) )
    $invalidFields[] = "Difficulty";

  if ( !$Test->setNumQuestions($_POST["number-of-questions"]) )
    $invalidFields[] = "Number of questions";

  if ( $custom ) {
    $Test->setCustom(true);

    if ( !$Test->setTeacherName($_POST["teacher-name"]) )
      $invalidFields[] = "Teacher's name";

    if ( !$Test->setMessage($_POST["message"]) )
      $invalidFields[] = "Message";
  }

  // If none of the fields are invalid, insert the test into the database.
  if ( empty($invalidFields) ) {
    $Test->insertTest();
    header("Location: ../test/" . $Test->getLink());
  } 
}
else {
  header("Location: ../../test");
}
