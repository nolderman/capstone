<?php
require_once 'connect.php'; //connect to the database

if(isset($_POST['message'])){ //if the user clicks the submit button on the groupPage
	if($_POST["message"])
		PostMessage($conn);
	header('Location: http://glados/capstone/group.html'); //go back to the group page without doing anything
}

function PostMessage($connection){
	
	$dateTime = new DateTime();
	$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$message = $_POST['message']; //get the message that was posted 
	
	//Insert the message with the user who posted, group posted to, dateTime posted, and the message itself.
	$sql = "INSERT INTO messages (email, groupID, dateTimePosted, messageData) VALUES('$_COOKIE[email]', '$_COOKIE[groupID]', '$dateTime', '$message')";
	$result= $connection->query($sql);
	
	header('Location: http://glados/capstone/group.html'); //go back to the group page
}
?>