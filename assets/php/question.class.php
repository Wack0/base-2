<?php

// Generates random questions based on a set difficulty level.
class Question {
  private $question;
  private $answer;
  private $bits;
  private $highestValue;

  // The difficulty is passed in when the object is created and 
  // it defines the number of bits that will be used in the 
  // questions and highest value a number will have.
  public function __construct($difficulty)
  {
    $this->difficulty = $difficulty;

    switch ($this->difficulty) {
      case "easy":
        $this->bits = 4;
        $this->highestValue = 32;
        break;
      case "hard":
        $this->bits = 12;
        $this->highestValue = 128;
        break;
      default:
        $this->bits = 8;
        $this->highestValue = 512;
        break;
    }

    // Randomly pick from a choice of 4 question types.
    switch (rand(0, 3)) {
      case 0:
        $this->generateAdditionQuestion();
        break;
      case 1:
        $this->generateSubtractionQuestion();
        break;
      case 2:
        $this->generateDecimalToBinaryQuestion();
        break;
      case 3:
        $this->generateBinaryToDecimalQuestion();
        break;
    }
  }

  // Generates a string of random 1s and 0s.
  private function generateRandomBits($bits)
  {
    $bitString = "";
    for ( $i = 0; $i < $bits; $i++ ) { 
      $bitString .= rand(0, 1);
    }

    return $bitString;
  }

  // Creates a binary addition question in the form:
  // What is 0000000 + 0000000?
  private function generateAdditionQuestion()
  {
    $binary = new Binary();
    
    $x = $this->generateRandomBits($this->bits);
    $y = $this->generateRandomBits($this->bits);

    $this->question = "What is $x + $y?";
    // Use a binary object to add the numbers together.
    $this->answer = $binary->binaryAdd($x, $y);
  }

  // Creates a binary subtraction question in the form:
  // What is 0000000 - 0000000?
  private function generateSubtractionQuestion()
  {
    $binary = new Binary();
    
    $x = $this->generateRandomBits($this->bits);
    $y = $this->generateRandomBits($this->bits);

    // Keep generating new bit strings until the value is less than than the
    // previous. This stops questions haveing negative answers.
    while ( $y > $x ) {
      $y = $this->generateRandomBits($this->bits);
    }

    $this->question = "What is $x - $y?";
    $this->answer = $binary->binarySub($x, $y);
  }

  // Generates a question that asks the user to convert a number from
  // binary to decimal.
  private function generateBinaryToDecimalQuestion()
  {
    $binary = new Binary();

    $x = $this->generateRandomBits($this->bits);

    $this->question = "Convert $x into decimal.";
    $this->answer = $binary->binaryToDecimal($x);
  }

  // Generates a question that asks the user to convert a number from
  // decimal to binary.
  private function generateDecimalToBinaryQuestion()
  {
    $binary = new Binary();

    $x = rand(0, $this->highestValue);

    $this->question = "Convert $x into binary.";
    $this->answer = $binary->decimalToBinary($x);
  }

  // Returns the generated question.
  public function getQuestion()
  {
    return $this->question;
  }

  // Returns the correct answer for the question.
  public function getAnswer()
  {
    return $this->answer;
  }
}
