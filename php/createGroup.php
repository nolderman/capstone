<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST["groupName"])){
	if($_POST["groupName"] != "") //only save a contact if the user put something in the submit box
		CreateGroup($conn);
	header('Location: http://glados/capstone/group.html');
}

function CreateGroup($connection){
	$dateTime = new DateTime();
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	$groupName = $_POST['groupName'];
	//insert my email, contacts email, and the current dateTime
		//*********************Need to make sure the contactEmail is in the database before adding them
	$sql = "INSERT INTO groups (groupID, groupName, dateTimeCreated, moderator) VALUES (NULL,'$_POST[groupName]', '$dateTime',  '$_COOKIE[email]')"; //put the contact in the database
	$result = $connection->query($sql);
	
	
	
	$sql = "SELECT groupID, groupName, dateTimeCreated, moderator FROM groups WHERE groupName='$_POST[groupName]' AND dateTimeCreated='$dateTime'";
	$result = $connection->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC); //get the group we inserted
	
	$groupID = $row['groupID'];
	$insertMember = "INSERT INTO groupMembership (groupID, email) VALUES ('$groupID', '$_COOKIE[email]')";
	$insertResult = $connection->query($insertMember);
	
	setcookie('groupID' , $row['groupID'], time()+60*60*24, '/'); //set the groupID cookie
	setCookie('groupName', $row['groupName'], time()+60*60*24, '/'); //set the groupName
	
 	header('Location: http://glados/capstone/group.html');
}

?>