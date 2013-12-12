<?php

class ViewResult extends Result {
  public function selectUsersResults($testID, $userID)
  {
    $db = $this->database;
    $sql = "SELECT * FROM tbl_results WHERE test_id = :test_id AND user_id = :user_id";
    $qry = $db->prepare($sql);
    $qry->execute(array(":test_id" => $testID, ":user_id" => $userID));

    if ( $qry->rowCount() === 0 ) {
      return $qry;
    }
    else {
      $results = array();
      foreach ($qry as $result) {
        $results[] = $result;
      }
      return $results;
    }
  }

  public function selectAllResults($testID)
  {
    $db = $this->database;
    // $sql = "SELECT * FROM tbl_results WHERE test_id = :test_id";
    $sql = "SELECT * 
            FROM tbl_results 
            LEFT JOIN tbl_users 
            ON tbl_results.user_id = tbl_users.user_id
            WHERE test_id = :test_id
            ORDER BY user_name ASC";

    $qry = $db->prepare($sql);
    $qry->execute(array(":test_id" => $testID));

    if ( $qry->rowCount() === 0 ) {
      return $qry;
    }
    else {
      $results = array();
      foreach ($qry as $result) {
        $results[] = $result;
      }
      return $results;
    }
  }
}
