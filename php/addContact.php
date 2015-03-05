<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST["contactEmail"])){
	if($_POST["contactEmail"] != "") //only save a contact if the user put something in the submit box
		AddContact($conn);
	header('Location: http://glados/capstone/profile.html');
}

function AddContact($connection){
	$dateTime = new DateTime();
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	//insert my email, contacts email, and the current dateTime
		//*********************Need to make sure the contactEmail is in the database before adding them
	$sql = "INSERT INTO contacts (myEmail, contactEmail, dateConnected) VALUES ('$_COOKIE[email]', '$_POST[contactEmail]', '$dateTime')"; //put the contact in the database

	$result = $connection->query($sql);
	header('Location: http://glados/capstone/profile.html');
}

?>