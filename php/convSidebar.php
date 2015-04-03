<?php
	require_once 'connect.php';
	session_start(); //fetch the session variables from the database
	
	$user = $_SESSION["uID"];
	$numConversations = 10;

	echo "<div class='sidebarHeader'>Conversations</div>";
		$sql = "SELECT (cID, c_name) FROM (user NATURAL JOIN participates NATURAL JOIN conversation) WHERE (uID = '$user')"; //put the contact in the database
		echo "<div class='sidebarContent'>";
			if($result = $conn->query($sql)){
				//write out each conversation name to the sidebar
				while($convos = $result->fetch_array(MYSQLI_ASSOC)){
					echo "<a href = 'conversation.php?cID=".$convos['cid']."'>";
					echo "<div class='convLink hvr-fade-green'>".$convos['c_name']."</div>";
					echo "</a></br>";		
				}	
			}
			else{
				echo "<div class='convLink'>You have no conversations!</div>";
			}
		echo "</div>";
?>