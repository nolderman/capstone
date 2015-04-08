<?php
	require_once 'connect.php';
	require_once 'functions.php';
	require_once 'sessionStatus.php';
	require_once 'getProfileInfo.php';
	
	
	
	if(isset($_GET['blocked'])){
		$blockedUser = $_GET['blocked'];
		echo $blockedUser;
	}
	if(!$blockedUser){
		BlockUser($connection);
	}else{
		UnBlockUser($connection);
	}
	
function BlockUser($connection){

	//insert uID and contacts uID
	$contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
	$uID = $_SESSION["uID"];//the user's ID
	
	$sql = "INSERT INTO u_blocks (uID, blocked) VALUES ('$uID', '$contactID')"; //put the block in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}	
	
function UnBlockUser($connection){

	//insert uID and contacts uID
	$contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
	$uID = $_SESSION["uID"];//the user's ID
	
	$sql = "DELETE FROM u_blocks WHERE uID='$uID' AND blocked='$contactID'"; //remove the block from the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}
?>