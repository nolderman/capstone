<?php
	require_once 'connect.php';
	session_start(); //fetch the session variables from the database
	
	$user = $_SESSION['uID'];
	$numConversations = 10;


	echo "<div class='sidebarHeader' align='center'>Groups</div>";
	
		$sql = "SELECT (gID, g_name) FROM (user NATURAL JOIN members NATURAL JOIN groups) WHERE (uID ='$user')"; //put the contact in the database
		
		if($result = $conn->query($sql)){
			//write out each group name to the sidebar and make them links
			echo "<div class='sidebarContent' align='center'>";
			while($groups = $result->fetch_assoc()){
				echo "<a href = 'group.php?gID=".$groups['gid']."'>";
				echo "<div class='groupLink'>".$groups['g_name']."</div>";
				echo "</a></br>";
			}
			echo "</div>";
		}
		else{
			echo "<div class='groupLink'>You have no groups!</div>";
		}
?>