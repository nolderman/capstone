<?php
require_once 'connect.php';

if(true){ //isset(_POST["submit"])  not working right now //if the Login button on index.html is set then do the logging in
	logIn($conn);
}

function logIn($connection) {
	//grab info from index.html 
	$email = $_POST['usermail'];
	$password = $_POST['password'];

	$password = sha1($password, $raw_output = false); //get the encrypted password
	
	$sql = "SELECT pass FROM profile WHERE email = $email ";//encrypted password of the user
	$result = $connection->query($sql);
	
	if($result == $pass){//the user was indeed in the table and had the correct password
		echo "Made it";
		//start the session and initialize some cookies
		session_start();
        $_SESSION['email']= $_POST['userMail'];
        $_SESSION['password']=$password; //save the encrypted version of their password

		setcookie("email", $_SESSION['email'], time()+60*60*24);
        setcookie("password", $_SESSION['password'], time()+60*60*24);
		header("location:../profile.html");//go to the profile page for the user somehow
	}
}
?>