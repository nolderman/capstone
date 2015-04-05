<?php
//set up the session
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

//redirect to index page
header('Location: ../index.php');		
?>