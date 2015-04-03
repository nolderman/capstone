<?php
session_start();
$_SESSION['uID'] = 'NULL';
header('Location: ../index.php');		//redirect to the loginpage.html
?>