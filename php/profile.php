<?php
require_once 'connect.php';
require_once 'userLogin.php';

if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}



function getPicture(){
	$picture = mysql_query("SELECT profilePicture FROM profile WHERE email = '$_POST[eMail]'") or die(mysql_error());

	// $firstName = $_POST['firstName'];
	// $lastName = $_POST['lastName'];
	// $email = $_POST['eMail'];
	// $password =  $_POST['password'];
	// $pass = sha1($password, $raw_output = false);
	// $query = "INSERT INTO profile (f_Name,l_Name,email,pass) VALUES ('$firstName','$lastName','$email','$pass')";
	// $data = mysql_query ($query)or die(mysql_error());
}

function getTags(){
	$tags = mysql_query("SELECT tags FROM profile WHERE email = '$_POST[eMail]'") or die(mysql_error());
}

function getContacts($conn, $email){
	$contacts = mysql_query("SELECT contacts FROM profile WHERE email = '$_POST[eMail]'") or die(mysql_error());
}




?>