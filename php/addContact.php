<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST["contactEmail"])){
	if($_POST["contactEmail"] != "")
		AddContact($conn);
	header('Location: http://glados/capstone/profile.html');
}

function AddContact($connection){
	$dateTime = new DateTime();
	$result = $dateTime->format('Y-m-d H:i:s');
	//insert my email, contacts email, and the current dateTime
		//*********************Need to make sure the contactEmail is in the database
	$sql = "INSERT INTO contacts (myEmail, contactEmail, dateConnected) VALUES ('$_COOKIE[email]', '$_POST[contactEmail]', '$result')";

	
	$result = $connection->query($sql);
	header('Location: http://glados/capstone/profile.html');
}

?>