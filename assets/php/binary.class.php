<?php

class Binary {
  // Adds two binary numbers together and returns the answer.
  public function binaryAdd($x, $y)
  {
    $xDec = base_convert($x, 2, 10);
    $yDec = base_convert($y, 2, 10);

    return $xDec + $yDec;
  }

  // Subtracts one binary number from another and returns the answer.
  public function binarySub($x, $y)
  {
    $xDec = base_convert($x, 2, 10);
    $yDec = base_convert($y, 2, 10);

    return $xDec - $yDec;
  }

  // Converts a binary number to decimal and returns it.
  public function binaryToDecimal($x)
  {
    return base_convert($x, 2, 10);
  }

  // Converts a decimal number to binary and returns it.
  public function decimalToBinary($x)
  {
    return base_convert($x, 10, 2);
  }
}
