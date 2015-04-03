<?php
require_once 'login.php';

$conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_database); //connects to the database

if(mysqli_connect_errno()) {
  printf('Connect failed: %s\n', mysqli_connect_error());
  exit();
}
?>