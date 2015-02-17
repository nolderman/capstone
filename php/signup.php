<?php
require_once 'connect.php';

function NewUser(){
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['eMail'];
	$password =  $_POST['password'];
	$pass = sha1($password, $raw_output = false);
	$query = "INSERT INTO profile (f_Name,l_Name,email,pass) VALUES ('$firstName','$lastName','$email','$pass')";
	$data = mysql_query ($query)or die(mysql_error());
}

function SignUp(){
	if(!empty($_POST['firstName'])){  //checking the 'firstName' which is from register.html
		//email from table and getting the POST[eMail] from register.html
		$query = mysql_query("SELECT * FROM profile WHERE email = '$_POST[eMail]' AND pass = '$_POST[password]'") or die(mysql_error());
	
		if(!$row = mysql_fetch_array($query) or die(mysql_error())){
			newuser();
		}
		else{
			echo "SORRY...YOU ARE ALREADY REGISTERED USER...";
		}
	}
}

if(isset($_POST['submit'])){
	SignUp();
}
?>