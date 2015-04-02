<?php
session_start();
$_SESSION['uID'] = 'NULL';
header('Location: http://glados/capstone');		//redirect to the loginpage.html
?>