<?php
	require_once 'connect.php';
	require_once 'functions.php';

	$numConversations = 10;

	echo "<h1>Conversations</h1>";

	$sql = "SELECT c_name FROM user NATURAL JOIN participates NATURAL JOIN conversation VALUES $_SESSION["uID"]"; //put the contact in the database
	$result = $conn->query($sql);
	$convNames = $result->fetch_array()

	echo "<ul>"; //start an unordered list in html
	for(int i = 0; i < $numConversations; i++){

	}
	echo "</ul>"; //end the unordered list in html
?>