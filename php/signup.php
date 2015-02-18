<?php
require_once 'connect.php';

function NewUser($connection){
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	
	$email = $_POST['eMail'];
	
	$password =  $_POST['password'];
	
	$pass = sha1($password, $raw_output = false);
	
	$sql= "INSERT INTO profile (f_Name,l_Name,email,pass) VALUES ('$firstName','$lastName','$email','$pass')";
	$result = $connection->query($sql);
	
	header('Location: http://glados/capstone/index.html');		//redirect to the loginpage.html
}

function SignUp($connection){
	
	if(!empty($_POST['firstName'])){  //checking the 'firstName' which is from register.html
		//email from table and getting the POST[eMail] from register.html
		
		$sql = "SELECT * FROM profile WHERE email = '$_POST[eMail]' AND pass = '$_POST[password]'";
		$result = $connection->query($sql);
	
		if($result){
			newuser($connection);
		}
		else{
			echo "SORRY...YOU ARE ALREADY REGISTERED USER...";
		}
	}
}

if(isset($_POST['submit'])){
	SignUp($conn);
}
?>