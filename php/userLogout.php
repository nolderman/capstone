<?php
include "sessionStatus.php";

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

//redirect to index page
header('Location: ../index.php');		
?>