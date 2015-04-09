<?php
//if no conversation is set, redirect to profile page
if(!isset($_GET["cID"])){
	header('Location: /profile.php');
}

//sets the basic variables
$user = $_SESSION["uID"];
$cID = $_GET["cID"];
$ownsPage = True;//so the conversation sidebar doesn't try to find convos in common with anyone


//query database for the conversation info that will be needed
//first check if this user is a participant of the conversation, and direct away if they aren't
$sql = "SELECT uID
		FROM (participates)
		WHERE (uID = '$user' AND cID = '$cID')";
$result = $connection->query($sql);
if($result->num_rows == 0){
	header('Location: ../profile.php');
}

//second get the convo name
$sql = "SELECT c_name
		FROM (conversation)
		WHERE (cID = '$cID')";
$result = $connection->query($sql);
$getName = $result->fetch_array(MYSQLI_ASSOC);
$convoName = $getName["c_name"];

//then get the messages
$sql = "SELECT *
		FROM (message)
		WHERE (cID = '$cID')";
$result = $connection->query($sql);
$messages = $result->fetch_array(MYSQLI_ASSOC);

?>