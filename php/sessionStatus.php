<?php
//set up the session
$sessionID = session_id();
if($sessionID == '') {//session_status() == PHP_SESSION_NONE
	session_start();
}

//COMMENTED OUT FOR TESTING - uncomment to redirect user to index page if they aren't logged in
// if(!isset($_SESSION["uID"])){
// 	header('Location: ../index.php');
// }
?>