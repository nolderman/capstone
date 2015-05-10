<?php
$db_hostname = 'localhost'; //The information for my wamp server (if this is reproduced all of the code will work the same on another server)
$db_database = 'capstone';
$db_username = 'root';
$db_password = 'grassWaffleSealIndex';

$connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database); //connects to the database

if(mysqli_connect_errno()) {
  printf('Connect failed: %s\n', mysqli_connect_error());
  exit();
}
?>