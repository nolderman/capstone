<?php
	require_once 'connect.php'; //connect to the database
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
	
	if(isset($_POST['postMessage'])){ //if the user clicks the submit button on the groupPage
		if(isset($_POST['message']))
			PostMessage($conn);
		header('Location: ../group.php'); //go back to the group page without doing anything
	}

	function PostMessage($connection){
		
		$dateTime = new DateTime();
		$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
		
		$message = $_POST['message']; //get the message that was posted 
		
		//Insert the message with the user who posted, group posted to, dateTime posted, and the message itself.
		$uID = $_SESSION['uID'];
		$gID = $_SESSION['gID'];
		echo $uID."-";
		echo $gID."-";
		echo $dateTime;
		echo $message;
		$sql = "INSERT INTO post (uID, gID, date_time, content, edited) VALUES ('$uID', '$gID', '$dateTime', '$message', '0')";
		$result= $connection->query($sql);
	
		header('Location: ../group.php'); //go back to the group page
	}
?>