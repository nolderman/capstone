<?php
//this file sets up the variables the conversation page will need
//Variables:
//$user - the user's ID
//$cID - the conversation's ID
//$convoName - the name of this conversation (name only exists if the convo is attached to a group)
//$joined - date the user joined the conversation

//if no conversation is set, redirect to profile page
if(!isset($_GET["cID"])){
	header('Location: profile.php');
}

//sets the basic variables
$user = $_SESSION["uID"];
$cID = $_GET["cID"];


//query database for the conversation info that will be needed
//first, check if this user is a participant of the conversation, and direct away if they aren't
$sql = "SELECT uID, joined
		FROM (participates)
		WHERE (uID = '$user' AND cID = '$cID')";
$result = $connection->query($sql);
if($result->num_rows == 0){
	header('Location: profile.php');
}
else{
	$participant = $result->fetch_array(MYSQLI_ASSOC);
	$joined = $participant["joined"];
}

//second, get the convo name
$sql = "SELECT c_name
		FROM (conversation)
		WHERE (cID = '$cID')";
$result = $connection->query($sql);
$getName = $result->fetch_array(MYSQLI_ASSOC);
$convoName = $getName["c_name"];

//third set as this user read the messages
$updateUnreadQuery = "UPDATE participates
                      SET unread_count = '0'
                      WHERE (uID = '$user' AND cID = '$cID')";
$connection->query($updateUnreadQuery);
?>