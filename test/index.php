<?php

$mode = "create";

if ( isset($_GET["testid"]) ) {
  if ( preg_match("/\d{6}/", $_GET["testid"]) ) {
    include "../assets/php/test.class.php";
    include "../assets/php/view-test.class.php";
    include "../assets/php/database.class.php";
    $database = new Database();
    $test = new ViewTest($database->getDatabase());
    $testlink = $_GET["testid"];

    if ( !$test->setTestLink($testlink) ) {
      $mode = "invalid";
    }
    else {
      $test->selectTest();

      if ( isset($_POST["sit-exam"]) || !$test->isCustom() ) {
        $mode = "sit-exam";
      }
      else {
        $mode = "view";
      }
    }
  }
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
  <link rel="stylesheet" href="../assets/css/global.css">
  <!--[if IE]>
    <script src="../assets/js/modernizr.min.js"></script>
  <![endif]-->
</head>
<body class="test">
  <div class="wrapper">
    <header id="site-header">
      <div>
        <a href="../" title="Home"><h1 id="site-title">Base-2</h1></a>
        <span id="site-tagline">A tool to help you learn and test your binary knowledge</span>
        <span id="site-super">by James Turner</span>
      </div>
      <nav id="site-nav">
        <ul>
          <li class="home"><a href="../"><img src="../assets/img/home-icon.png" alt="Home"></a></li><!--
       --><li class="learn"><a href="../learn">Learn</a></li><!--
       --><li class="test current"><a href="../test">Test</a></li><!--
       --><li class="calculate"><a href="../calculate">Calculate</a></li>
        </ul>
      </nav>
    </header>
    <div id="main">
      <?php if ( $mode === "create" ) { ?>
      <nav id="page-toggle">
        <ul>
          <li class="current">
            <a href="../test">Take a test</a>
          </li>
          <li>
            <a href="create">Create a test</a>
          </li>
        </ul>
      </nav>
      <p>Take a test to find out how well you've grasped binary, or if you're a teacher you could <a href="create">create a custom test</a> for your students!</p>
      <form id="create-test-form" action="create" method="post">
        <label for="difficulty">Difficulty:</label>
        <select id="difficulty" name="difficulty">
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
        <p class="difficulty-desc"><strong>Easy</strong> - You'll get questions with only 4 bits involved.</p>
        <p class="difficulty-desc"><strong>Medium</strong> - Slightly harder questions with 8 bits.</p>
        <p class="difficulty-desc"><strong>Hard</strong> - You're on your own with 12 bits.</p>
        <label for="number-of-questions">Number of Questions:</label>
        <input name="number-of-questions" id="number-of-questions" type="number" value="5" min="1" max="100"><br>
        <input type="submit" name="create" value="Begin test!">
      </form>
      <?php } elseif ( $mode === "invalid" ) { ?>
      <h1>Sorry, the test you're looking for doesn't exist.</h1>
      <p>If you want, you can create your own test <a href="create">here.</a></p>
      <?php } elseif ( $mode === "sit-exam" ) { ?>
      <?php if ( $test->isCustom() ) { ?>
      <em>This test was created by <strong><?php echo $test->getTeacherName(); ?></strong> on <strong><?php echo $test->getDateCreated(true); ?></strong>.</em>
      <?php } ?>

      <form id="take-test-form" method="post" action="../assets/php/submit.php">
      <?php 
        
        $questions = $test->selectQuestions();

        echo "<ol id='question-list'>";
        foreach ($questions as $question) {
          echo "<li>";
          echo "<span>" . $question["question_body"] . "</span><br>";

          if ( preg_match("/^.*binary/", $question["question_body"]) ) {
            echo "<span class='answer-format'>Answer the question in binary.</span>";
          }
          else {
            echo "<span class='answer-format'>Answer the question in decimal.</span>";
          }
          
          echo "<input type='text' name='answers[]' class='answer-input'>";
          echo "</li>";
        }
        echo "</ol>";
      ?>
        <input type="hidden" name="name" value="<?php echo (isset($_POST["student-name"]) ? $_POST["student-name"] : "null") ?>">
        <input type="hidden" name="test-id" value="<?php echo $test->getID(); ?>">
        <input type="submit" name="submit-test" value="Submit!">
      </form>
      <?php } elseif ( $mode === "view" ) { ?>

      <em>This test was created by <strong><?php echo $test->getTeacherName(); ?></strong> on <strong><?php echo $test->getDateCreated(true); ?></strong>.</em>

      <form id="start-test-form" method="post" action="../test/<?php echo $test->getLink(); ?>">
        <label for="student-name">Name:</label>
        <input type="text" id="student-name" name="student-name" maxlength="70" autofocus required><br>

        <label>Difficulty:</label>
        <span class="test-setting"><?php echo $test->getDifficulty(true); ?></span><br>

        <label>Number of questions:</label>
        <span class="test-setting"><?php echo $test->getNumQuestions(); ?></span><br>

        <label>Message:</label>
        <span class="test-setting"><?php echo $test->getMessage(); ?></span><br>

        <input type="submit" name="sit-exam" value="Begin test!">
        <a href="../test/<?php echo $test->getLink(); ?>/results" target="_blank" class="btn">View Results!</a><br>
      </form>
      <?php } ?>
    </div>
  </div>
</body>
</html>
