<?php
require_once connect.php;

$email = $_POST['usermail'];
$password = $_POST['password'];
$pass = sha1($password, $raw_output = false); //get the encrypted password

LogIn(){
	
	$query = "SELECT pass FROM profile WHERE email = $email ";//encrypted password of the user
	
	if($query == $pass){
		
	}
	
	
}

if(isset($_POST['Login'])){ //if the Login button on index.html is set then do the logging in
	LogIn();
}
?>