<?php
require_once 'connect.php';
require_once 'sessionStatus.php';

if(isset($_POST['postMessage']) && isset($_POST['message'])){ //if the user clicks the submit button on the groupPage
	PostMessage($connection);
}


function PostMessage($connection){
	$dateTime = new DateTime();
	$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$message = $_POST['message']; //get the message that was posted 
	
	//Insert the message with the user who posted, group posted to, dateTime posted, and the message itself.
	$uID = $_SESSION['uID'];
	$gID = $_GET['gID'];
	echo $gID;
	
	$sql = "INSERT INTO post (uID, gID, date_time, content, edited) VALUES ('$uID', '$gID', '$dateTime', '$message', '0')";
	$result = $connection->query($sql);
	
	header("Location: ../group.php?gID=$gID"); //go back to the group page
}
?>