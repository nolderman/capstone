<?php
	require_once 'connect.php';
	require_once 'functions.php';
	require_once 'sessionStatus.php';
	require_once 'getProfileInfo.php';

	
RemoveContact($connection);
	
function RemoveContact($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	//insert uID, contacts uID, and the current dateTime
	$contactID = $_GET["uID"]; //the user id of the contact
	$uID = $_SESSION["uID"];//the user's ID
	echo $uID;
	echo $contactID;

	$sql = "DELETE FROM contacts WHERE uID='$uID' AND contact='$contactID'"; //Remove the contact in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}
?>