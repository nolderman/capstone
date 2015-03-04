<?php
require_once 'connect.php';

if(isset($_POST['submit'])){ //if the Login button on index.html is set then do the logging in
		LogIn($conn);
}

function LogIn($connection) {
	//grab info from index.html 
	$emailname = "email";
	$passname = "password";
	$email = $_POST['usermail'];
	
	$password = $_POST['password'];
	
	$password = sha1($password, $raw_output = false); //get the encrypted password
	
	$sql = "SELECT pass FROM profile WHERE email = '$email '";//encrypted password of the user

	$result = $connection->query($sql);
	$row = $result->fetch_assoc();
	
	if($password == $row['pass'] ){//FIX THE PASSWORD COMPARISON the user was indeed in the table and had the correct password
		setcookie($emailname , $email, time()+60*60*24, '/');
		header('Location: http://glados/capstone/profile.html');
	}else{
		echo "Sorry you entered the wrong password";
	}
}
?>