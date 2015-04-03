<?php
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
	require_once 'connect.php';
	require_once 'functions.php';

	if(isset($_POST['contactEmail'])){
		if(isset($_POST['contactEmail'])) //only save a contact if the user put something in the submit box
			AddContact($conn);
		header('Location: ../profile.php');
	}

	function AddContact($connection){
		$dateTime = new DateTime();
		$dateTime = $dateTime->format('Y-m-d H:i:s');
		//insert my email, contacts email, and the current dateTime
		$contactEmail = $_POST['contactEmail'];
		$uID = $_SESSION['uID'];
		
		$getContactID = "SELECT uID FROM user WHERE email= '$contactEmail'";
		$result = $connection->query($getContactID);
		$row = fetch_array(MYSQLI_ASSOC);
		$contactID = $row['uID'];
		
		//*********************Need to make sure the contactEmail is in the database before adding them
		$sql = "INSERT INTO contacts (user, contact) VALUES ('$uID', '$contactID')"; //put the contact in the database
		$result = $connection->query($sql);
		
		header('Location: ../profile.php');
	}
?>