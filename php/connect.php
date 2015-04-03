<?php
require_once 'login.php';

$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database); //connects to the database

if ($conn->connect_error) {
  printf('Connect failed: %s\n', mysqli_connect_error());
  exit();
}
?>