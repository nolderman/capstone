<?php
require_once 'connect.php';
require_once 'functions.php';

GetConversations();

function GetConversations(){
	echo "<h1>Conversations</h1>";

	$sql = "INSERT INTO contacts (myEmail, contactEmail, dateConnected) VALUES ('$_COOKIE[email]', '$_POST[contactEmail]', '$dateTime')"; //put the contact in the database

	$result = $conn->query($sql);
	
	header('Location: http://glados/capstone/profile.html');
}

?>