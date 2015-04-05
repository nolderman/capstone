<?php
//set up the session
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

if(!isset($_SESSION["uID"])){
	header('Location: index.php');
}
?>