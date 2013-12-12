<?php
include "../assets/php/functions.php";

if ( !isset($_GET["testid"]) ) {
  header("Location: ../../test");
  return;
}

session_start();

if ( isset($_SESSION["userid"]) ) {
  $testLink = $_GET["testid"];
  $userID = $_SESSION["userid"];
  unset($_SESSION["userid"]);
  session_destroy();
  if ( preg_match("/\d{6}/", $testLink) ) {
    include "../assets/php/database.class.php";
    include "../assets/php/test.class.php";
    include "../assets/php/view-test.class.php";
    include "../assets/php/result.class.php";
    include "../assets/php/view-result.class.php";

    $database = new Database();
    $result = new ViewResult($database->getDatabase());
    $test = new ViewTest($database->getDatabase());

    if ( $test->setTestLink($testLink) ) {
      if ( $test->selectTest() ) {
        $questions = $test->selectQuestions();
        $results = $result->selectUsersResults($test->getID(), $userID);
      }
    }
    else {
      header("Location: ../../test");
    }
  }
  else {
    header("Location: ../../test");
  }
}
else {
  header("Location: ../../test");
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Test &#8226; Base-2 &#8226; A tool to help you learn and test your binary knowledge</title>
  <meta name="author" content="James Turner">
  <meta name="description" content="A tool to help you learn and test your binary knowledge">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="../../assets/css/global.css">
  <!--[if IE]>
    <script src="../assets/js/modernizr.min.js"></script>
  <![endif]-->
</head>
<body class="test">
  <div class="wrapper">
    <header id="site-header">
      <div>
        <a href="../../" title="Home"><h1 id="site-title">Base-2</h1></a>
        <span id="site-tagline">A tool to help you learn and test your binary knowledge</span>
        <span id="site-super">by James Turner</span>
      </div>
      <nav id="site-nav">
        <ul>
          <li class="home"><a href="../../"><img src="../../assets/img/home-icon.png" alt="Home"></a></li><!--
       --><li class="learn"><a href="../../learn">Learn</a></li><!--
       --><li class="test current"><a href="../../test">Test</a></li><!--
       --><li class="calculate"><a href="../../calculate">Calculate</a></li>
        </ul>
      </nav>
    </header>
    <div id="main">
      <?php
        $numCorrect = 0;
        $total = count($questions);
        foreach ( $results as $result ) {
          if ( $result["result"] === "1" ) {
            $numCorrect++;
          }
        }
        $percentage = ($numCorrect / $total) * 100;
        $percentage *= 10;
        $percentage = round($percentage);
        $percentage /= 10;
      ?>
      <h1>Your Score - <span class="percentage"><?php echo $percentage; ?>%</span></h1>
      <p>You managed to score <?php echo $numCorrect; ?> out of <?php echo $total; ?>, good effort!</p>
      <ol id="question-list">
        <?php
        for ( $i = 0; $i < count($questions); $i++ ) {
          $result = $results[$i];
          $question = $questions[$i];

          $correct = $result["result"] === "1" ? true : false;
          $class = $correct ? "correct" : "incorrect";

          echo "<li>";
          echo "<span class='$class'>" . $question["question_body"] . "</span><br>";

          if ( $correct ) {
            echo "<span>Answer: " . $question["question_answer"] . " </span>";
          }
          else {
            echo "<span>You answered: " . $result["user_answer"] . " </span><br>";
            echo "<span>The correct answer was " . $question["question_answer"] . "</span>";
          }
          echo "</li>";
        }
        ?>
      </ol>
    </div>
  </div>
</body>
</html>

