<?php
	require_once 'connect.php';
	require_once 'functions.php';

	$user = $_SESSION["uID"];
	$numConversations = 10;


	echo "<div class='sidebarHeader' align='center'>Conversations</div>";
	
	$sql = "SELECT c_name FROM user NATURAL JOIN participates NATURAL JOIN conversation WHERE uID = '$user'"; //put the contact in the database
	$result = $conn->query($sql);
	$convNames = $result->fetch_array();

	//write out each conversation name to the sidebar
	echo "<div class='sidebarContent' align='center'>";
	for($i = 0; $i < $numConversations; $i++){
		echo "<div class='convLink'>".$convNames[$i]."<br></div>"; //list out the conversation names
	}
	echo "</div>";
?>