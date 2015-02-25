<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST['submit'])){
	SignUp($conn);
}

function SignUp($connection){
	
	if(!empty($_POST['firstName'])){  //checking the 'firstName' which is from register.html
		//email from table and getting the POST[eMail] from register.html
		$sql = "SELECT * FROM profile WHERE email = '$_POST[eMail]' AND pass = '$_POST[password]'";//
		$result = $connection->query($sql);
	
		if($result){
			newuser($connection);
		}
		else{
			echo "SORRY...YOU ARE ALREADY REGISTERED USER...";
		}
	}
}

function NewUser($connection){
	//Set the error variables to empty
	$firstNameErr = $lastNameErr = $emailErr = $passErr = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(empty($_POST['firstName'])){
			$firstNameErr = "First name is required.";
		}else{ 
			$firstName = test_Input($_POST['firstName']);
			if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
				$firstNameErr = "Only letters and white space allowed";
			}
		}
	
		if(empty($_POST['lastName'])){
			$lastNameErr = "Last name is required.";
		}else{
			$lastName = test_Input($_POST['lastName']);
			if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
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
		}else{$password =  test_Input($_POST['password']);}
	}
	$pass = sha1($password, $raw_output = false);
	
	$sql= "INSERT INTO profile (f_Name,l_Name,email,pass) VALUES ('$firstName','$lastName','$email','$pass')";
	$result = $connection->query($sql);
	
	header('Location: http://glados/capstone/index.html');		//redirect to the loginpage.html
}
?>