<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST['submit'])){
	SignUp($conn);
}

function SignUp($connection){
	if(!empty($_POST['firstName'])){  //making sure the user input a firstname
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
		
		if(empty($_POST['eMail'])){
			$emailErr = "Email is required";
		}else{
			$email = test_Input($_POST['eMail']);
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format";
			}
		}
	
		if(empty($_POST['password'])){
			$passErr = "Password is required";
		}
		else{
			$password =  test_Input($_POST['password']);
		}
	}
	$pass = sha1($password, $raw_output = false); //encrypt their password
	$sql= "INSERT INTO user (uID,email,pass,picture,f_Name,m_name,l_Name,tag_visibility,profile_visible,block_invites,block_messages) VALUES ('NULL','$email','$pass','NULL','$firstName','NULL','$lastName','1','1','0','0')"; //make them a profile
	$result = $connection->query($sql);

	
	$uID =  mysqli_insert_id($connection); //get the id of the last inserted record
	$uIDName = "uID";
	//session_start();
	$_SESSION['uID'] = $uID;
	//setcookie($uIDName, $uID, time()+60*60*24, '/');//set the user ID cookie for a day so we can get all of their information later
	//header('Location: ../profile.php');		//log the user in
}
?>