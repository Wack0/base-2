<?php

if ( !isset($_GET["testid"]) ) {
  header("Location: ../test");
}
else {
  include "../assets/php/database.class.php";
  include "../assets/php/test.class.php";
  include "../assets/php/view-test.class.php";
  include "../assets/php/result.class.php";
  include "../assets/php/view-result.class.php";

  $link = $_GET["testid"];
  $database = new Database();
  $test = new ViewTest($database->getDatabase());

  if ( $test->setTestLink($link) ) {
    $test->selectTest();
    if ( $test->isCustom() ) {
      $mode = "view";
    }
  }
  else {
    $mode = "invalid";
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
      if ( $mode === "view" ) { ?>

      <em>This test was created by <strong><?php echo $test->getTeacherName(); ?></strong> on <strong><?php echo $test->getDateCreated(true); ?></strong>.</em><br>
      <br>
      <label>Difficulty:</label>
      <span class="test-setting"><?php echo $test->getDifficulty(true); ?></span><br>

      <label>Number of questions:</label>
      <span class="test-setting"><?php echo $test->getNumQuestions(); ?></span><br>

      <label>Message:</label>
      <span class="test-setting"><?php echo $test->getMessage(); ?></span><br>


      <?php
        $result = new ViewResult($database->getDatabase());
        $results = $result->selectAllResults($test->getID());

        if ( !is_array($results) ) {
          if ( $results->rowCount() === 0 ) {
            echo "<strong>No results have been submitted yet.</strong>";
          }
        }

        else {
          $currentUser = null;
          foreach ( $results as $result ) {
            if ( $currentUser === $result["user_id"] ) {
              $users[$currentUser][] = $result;
            }
            else {
              $currentUser = $result["user_id"];
            }
          }

          $rows = array();

          foreach ( $users as $user ) {
            $numCorrect = 0;
            $numIncorrect = 0;
            foreach ( $user as $result ) {
              if ( $result["result"] === "1" ) {
                $numCorrect++;
              }
              else {
                $numIncorrect++;
              }
            }
            $total = $numCorrect + $numIncorrect;
            $percentage = ($numCorrect / $total) * 100;
            $percentage *= 10;
            $percentage = round($percentage);
            $percentage /= 10;

            $rows[] = array("name" => $user[0]["user_name"],
                        "score" => $numCorrect . "/" . $total,
                        "percentage" => $percentage);          
          }
        ?>
        <table>
          <thead>
            <tr>
              <td>Name</td>
              <td>Score</td>
              <td>Percentage</td>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ( $rows as $row ) {
              echo "<tr>";
              echo "<td>" . $row["name"] . "</td>";
              echo "<td>" . $row["score"] . "</td>";
              echo "<td>" . $row["percentage"] . "%</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
        <?php
        }
      } 
      ?>
      <?php if ( $mode === "invalid" ) { ?>
        <h1>Sorry, the test you're looking for doesn't exist.</h1>
        <p>If you want, you can create your own test <a href="../create">here.</a></p>
      <?php } ?>

    </div>
  </div>
</body>
</html>

