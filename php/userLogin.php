<?php
require_once 'connect.php';
session_start();

if(isset($_POST["submit"])){ //if the Login button on index.html is set then do the logging in
		LogIn($connection);
}

function LogIn($connection) {
	$emailname = "email"; //Constants used for cookie setting
	$passname = "password";
	
	$email = $_POST["usermail"];
	$password = $_POST["password"];
	$password = sha1($password, $raw_output = false); //Encrypt the password the user signed in with
	//$password = password_hash($password, PASSWORD_DEFAULT);
	$sql = "SELECT uID, pass, f_name, l_name FROM user WHERE email = '$email '";//ID and encrypted password of the user

	$result = $connection->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC); //get the array with uID and pass
	
	if($password == $row["pass"]){//compare the login password with the one they signed up with
		$_SESSION["uID"] = $row["uID"];
		$_SESSION["f_name"] = $row["f_name"];
		$_SESSION["l_name"] = $row["l_name"];
		
		session_write_close();
		header('Location: ../profile.php'); //go to their profile page
	} else{
		echo "Sorry you entered the wrong password";
	}
}
?>