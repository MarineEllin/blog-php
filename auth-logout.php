<?php 
  require_once __DIR__ . "/database/database.php";
  $authDB = require_once __DIR__ . "/database/security.php";
  $sessionid = $_COOKIE["session"];
  if($sessionid) {
    $authDB->logout($sessionid);
    header('Location: /auth-login.php');
  }

?>

