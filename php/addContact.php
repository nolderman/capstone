<?php
	require_once 'connect.php';
	require_once 'functions.php';
	session_start(); //fetch the session variables from the database

	if(isset($_POST['contactEmail'])){
		if(isset($_POST['contactEmail'])) //only save a contact if the user put something in the submit box
			AddContact($conn);
		header('Location: http://glados/capstone/profile.html');
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