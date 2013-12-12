<?php

function issetCheck ($variables, $method = "post") {
  // Make sure the passed variables are in an array.
  if ( !is_array($variables) ) {
    throw new Exception("Passed variables are not in an array.");
    return false;
  }

  foreach ( $variables as $var ) {

    switch ( strtolower($method) ) {
      // $_GET
      case "get":
        if ( !isset($_GET[$var]) ) {
          return false;
        }
        break;

      // $_SERVER
      case "server":
        if ( !isset($_SERVER[$var]) ) {
          return false;
        }
        break;

      // $_SESSION
      case "session":
        if ( !isset($_SESSION[$var]) ) {
          return false;
        }
        break;

      // $_POST is assumed by default.
      default:
        if ( !isset($_POST[$var]) ) {
          return false;
        }
        break;
    }
  }
  return true;
}
