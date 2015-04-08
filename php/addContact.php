<?php
	require_once 'functions.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/getProfileInfo.php';


function AddContact($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	//insert uID, contacts uID, and the current dateTime
	$contactID = $_GET["uID"]; //the user id of the contact
	$uID = $_SESSION["uID"];//the user's ID
	
	//*********************Need to make sure the contactEmail is in the database before adding them
	$sql = "INSERT INTO contacts (uID, contact) VALUES ('$uID', '$contactID')"; //put the contact in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}
?>