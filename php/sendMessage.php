<?php
require_once 'connect.php';
require_once 'sessionStatus.php';

//if the user clicks the submit button on the page
if(isset($_POST["sendMessage"]) && isset($_POST["message"])){ 
	PostMessage($connection);
}

function PostMessage($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$message = $_POST["message"]; //get the message that was posted 
	$uID = $_SESSION["uID"];
	$cID = $_GET["cID"];
	
	//insert the message into the database
	$sql = "INSERT INTO message (uID, cID, date_time, content) 
			VALUES ('$uID', '$cID', '$dateTime', '$message')";
	$result = $connection->query($sql);
	
	header("Location: ../conversation.php?cID=$cID"); //go back to the group page
}
?>