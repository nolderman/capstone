<?php
require_once 'connect.php'; //connect to the database

if(isset($_POST['postMessage'])){ //if the user clicks the submit button on the groupPage
	if($_POST["postMessage"]))
		PostMessage($conn);
	header('Location: http://glados/capstone/groupPage.html'); //go back to the group page without doing anything
}

function PostMessage($connection){
	
	$dateTime = new DateTime();
	$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$message = $_POST['postMessage']; //get the message that was posted 
	
	//Insert the message with the user who posted, group posted to, dateTime posted, and the message itself.
	$sql = "INSERT INTO message (email, groupID, dateTimePosted, messageData) VALUES('$_COOKIE['email'], '$COOKIE['groupID]', '$dateTime', '$messge')";
	$result= $connection->query($sql);
	
	header('Location: http://glados/capstone/groupPage.html'); //go back to the group page
}
?>