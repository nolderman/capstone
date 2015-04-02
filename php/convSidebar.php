<?php
	require_once 'connect.php';
	require_once 'functions.php';

	$user = $_SESSION["uID"];
	$numConversations = 10;


	echo "<div class='sidebarHeader' align='center'>Conversations</div>";
	
		$sql = "SELECT cID, c_name FROM user NATURAL JOIN participates NATURAL JOIN conversation WHERE uID = '".$user."'"; //put the contact in the database
		$result = $conn->query($sql);
		$conversations = $result->fetch_array();

		//write out each conversation name to the sidebar
		echo "<div class='sidebarContent' align='center'>";
		while($convos = $conversations->fetch_array(MYSQLI_ASSOC)){
			echo "<a href = 'group.html?gID=".$convos['cid']."'>";
			echo "<div class='convLink'>".$convos['c_name']."</div>";
			echo "</a></br>";		}

	echo "</div>";
?>