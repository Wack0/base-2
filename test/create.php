<?php

if ( isset($_POST["create"]) ) {
  include "../assets/php/create-test.php";

  if ( !empty($invalidFields) ) {
    $formInvalid = true;
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
      <nav id="page-toggle">
        <ul>
          <li>
            <a href="../test">Take a test</a>
          </li>
          <li class="current">
            <a href="create">Create a test</a>
          </li>
        </ul>
      </nav>
      <p>Create a test for your students to complete and view their results!</p>
      <form id="create-custom-test-form" action="create" method="post">
        <?php

        if ( isset($formInvalid) ) {
          if ($formInvalid) {
            echo "<div id='invalid-list'>";
            echo "<strong>Invalid Fields:</strong>";
            echo "<ul>";
            foreach ( $invalidFields as $field => $value ) {
              echo "<li>" . $value . "</li>";
            }
            echo "</ul>";
            echo "</div>";
          }
        }

        ?>
        <label for="teacher-name">Teacher's name:</label>
        <input maxlength="70" type="text" id="teacher-name" name="teacher-name"><br>
        <div id="message-side">
          <label for="message">Message:</label>
          <span>Optional</span>
          <span id="char-count">0/500</span>
        </div>
        <textarea maxlength="500" name="message" id="message"></textarea><br>
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
        <input type="submit" name="create" value="Create test!">
      </form>
    </div>
  </div>
  <script src="../src/js/char-count.js"></script>
</body>
</html>

