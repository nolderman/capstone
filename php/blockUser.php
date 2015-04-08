<?php
	require_once 'functions.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/getProfileInfo.php';


function BlockUser($connection){

	//insert uID and contacts uID
	$contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
	$uID = $_SESSION["uID"];//the user's ID
	
	//*********************Need to make sure the contactEmail is in the database before adding them
	$sql = "INSERT INTO u_blocks (uID, blocked) VALUES ('$uID', '$contactID')"; //put the contact in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}
?>