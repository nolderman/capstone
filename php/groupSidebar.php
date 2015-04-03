<?php
	require_once 'connect.php';
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
	
	$user = $_SESSION["uID"];
	$numConversations = 10;

	echo "<div class='sidebarHeader'>Groups</div>";
		$sql = "SELECT (gID, g_name) FROM (user NATURAL JOIN members NATURAL JOIN groups) WHERE (uID ='$user')"; //put the contact in the database
		echo "<div class='sidebarContent'>";
			if($result = $conn->query($sql)){
				//write out each group name to the sidebar and make them links
				while($groups = $result->fetch_assoc()){
					echo "<a href = 'group.php?gID=".$groups['gid']."'>";
					echo "<div class='groupLink'>".$groups['g_name']."</div>";
					echo "</a></br>";
				}
			}
			else{
				echo "<div class='groupLink'>You have no groups!</div>";
			}
		echo "</div>";
?>