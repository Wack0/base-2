<?php

class Result {
  protected $database;
  
  public function __construct($database)
  {
    $this->database = $database;
  }
}
