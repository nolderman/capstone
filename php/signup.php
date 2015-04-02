<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST['submit'])){
	SignUp($conn);
}

function SignUp($connection){
	
	if(!empty($_POST['firstName'])){  //making sure the user input a firstname
		echo "Sign up!";
		newuser($connection);
	}
}

function NewUser($connection){
	//Set the error variables to empty
	$firstNameErr = $lastNameErr = $emailErr = $passErr = ""; //NOT USED YET (meant for displaying user input errors on the signup page)
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(empty($_POST['firstName'])){
			$firstNameErr = "First name is required.";
		}else{ 
			$firstName = test_Input($_POST['firstName']);
			if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
				$firstNameErr = "Only letters and white space allowed";
			}
		}
	
		if(empty($_POST['lastName'])){
			$lastNameErr = "Last name is required.";
		}else{
			$lastName = test_Input($_POST['lastName']);
			if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
				$lastNameErr = "Only letters and white space allowed";
			}
		}
		echo $_POST['eMail'];
		if(empty($_POST['eMail'])){
			$emailErr = "Email is required";
		}else{
			$email = test_Input($_POST['eMail']);
			$emailname = "email";
			
			setcookie($emailname , $email, time()+60*60*24, '/'); //set the email cookie (log them in basically)
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format";
			}
		}
	
		if(empty($_POST['password'])){
			$passErr = "Password is required";
		}else{$password =  test_Input($_POST['password']);}
	}
	$pass = sha1($password, $raw_output = false); //encrypt their password
	echo "Sign up2!";																														//uID,email,pass,picture,f_Name,m_name,l_Name,tag_visibility,profile_visibility,block_invites,block_messages
	$sql= "INSERT INTO user (uID,email,pass,picture,f_Name,m_name,l_Name,tag_visibility,profile_visibility,block_invites,block_messages) VALUES ('NULL','$email','$pass','NULL','$firstName','NULL','$lastName','NULL','NULL','NULL','NULL')"; //make them a profile
	$result = $connection->query($sql);
	echo "Sign up3!";		
	//header('Location: http://glados/capstone/profile.html');		//log the user in
}
?>