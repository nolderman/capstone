<?php
require_once 'connect.php';

if(isset($_POST['submit'])){ //if the Login button on index.html is set then do the logging in
		LogIn($conn);
}

function LogIn($connection) {
	$emailname = "email"; //Constants used for cookie setting
	$passname = "password";
	
	$email = $_POST['usermail'];
	$password = $_POST['password'];
	$password = sha1($password, $raw_output = false); //Encrypt the password the user signed in with
	
	$sql = "SELECT pass FROM profile WHERE email = '$email '";//encrypted password of the user signed up with

	$result = $connection->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC); //get the array (just one password in this case)
	
	if($password == $row['pass'] ){//compare the login password with the one they signed up with
		setcookie($emailname , $email, time()+60*60*24, '/'); //set the email cookie
		header('Location: http://glados/capstone/profile.html'); //go to their profile page
	}else{
		echo "Sorry you entered the wrong password";
	}
}
?>