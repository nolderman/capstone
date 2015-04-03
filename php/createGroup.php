<?php
if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
}
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST["groupName"])){
	if(isset($_POST["groupName"])) //only save a contact if the user put something in the submit box
		CreateGroup($conn);
}

function CreateGroup($connection){

	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	
	$groupName = $_POST['groupName'];

	//make a new group
	$sql = "INSERT INTO groups (gID, g_name, icon, visible, burn_date) VALUES ('0','$groupName', 'NULL', '1', '0000-00-00 00:00:00')"; //put the contact in the database
	$result = $connection->query($sql);
	
	//Put the user in the member table with this group
	$uID = $_SESSION['uID']; 				//get the creator's uID
	$gID =  mysqli_insert_id($connection); //get the id of the last inserted record (in this case it is the group ID)

	$insertMember = "INSERT INTO members (uID,gID,moderator) VALUES ('$uID','$gID','1')";//set the creator to a moderator
	$insertMember = $connection->query($insertMember);
	
	$_SESSION['gID'] = $gID;
	$_SESSION['g_name'] = $groupName;
	header('Location: ../group.php');
}

?>