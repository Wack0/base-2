<?php

class User {
  protected $database;
  protected $name;
  protected $id;

  public function __construct($database)
  {
    $this->database = $database;
  }

  public function setUserName($name)
  {
    $this->name = substr($name, 0, 70);
  }

  public function insertUser()
  {
    $db = $this->database;
    $sql = "INSERT INTO tbl_users (user_name) VALUES (:name)";
    $qry = $db->prepare($sql);
    if ( $this->name != null ) {
      $qry->execute(array(":name" => $this->name));
    }
    else {
      $qry->execute(array(":name" => null));
    }

    $this->id = $db->lastInsertId();
    return $this->id;
  }

}
