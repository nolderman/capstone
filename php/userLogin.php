<?php
require_once 'connect.php';
session_start(); //fetch the session variables from the database

if(isset($_POST['submit'])){ //if the Login button on index.html is set then do the logging in
		LogIn($conn);
}

function LogIn($connection) {
	$emailname = "email"; //Constants used for cookie setting
	$passname = "password";
	
	$email = $_POST['usermail'];
	$password = $_POST['password'];
	$password = sha1($password, $raw_output = false); //Encrypt the password the user signed in with
	
	$sql = "SELECT uID, pass FROM user WHERE email = '$email '";//ID and encrypted password of the user

	$result = $connection->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC); //get the array with uID and pass
	
	if($password == $row['pass'] ){//compare the login password with the one they signed up with
		session_start();
		$_SESSION['uID'] = $row['uID'];
		//setcookie($emailname , $email, time()+60*60*24, '/'); //set the email cookie
		header('Location: ../profile.php'); //go to their profile page
	}else{
		echo "Sorry you entered the wrong password";
	}
}
?>