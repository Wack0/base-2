<?php

include "database.class.php";
include "test.class.php";
include "view-test.class.php";
include "user.class.php";
include "result.class.php";
include "create-result.class.php";
include "functions.php";

if ( issetCheck(array("submit-test", "test-id")) ) {
  $database = new Database();
  $test = new ViewTest($database->getDatabase());
  $user = new User($database->getDatabase());
  $results = new CreateResult($database->getDatabase());

  $userAnswers = $_POST["answers"];
  $testID = $_POST["test-id"];

  if ( $test->selectTest($testID ) ) {

    if ( $test->isCustom() ) {
      $user->setUserName($_POST["name"]);
    }

    $userID = $user->insertUser();

    $questions = $test->selectQuestions();

    if ( count($questions) ===  count($userAnswers) ) {
      $results->insertResults($testID, $userID, $questions, $userAnswers);
      session_start();
      $_SESSION["userid"] = $userID;
      header("Location: ../../test/" . $test->getLink() . "/score");

      return;
    }
  }
}
header("Location: ../../test");
