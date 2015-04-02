<?php
	require_once 'connect.php';

	$user = $_SESSION["uID"];
	$numConversations = 10;


	echo "<div class='sidebarHeader' align='center'>Groups</div>";
	
		$sql = "SELECT gID, g_name FROM user NATURAL JOIN members NATURAL JOIN groups WHERE uID ='".$user."'"; //put the contact in the database
		$result = $conn->query($sql);
		
		//write out each group name to the sidebar and make them links
		echo "<div class='sidebarContent' align='center'>";
		while($groups = $result->fetch_array(MYSQLI_ASSOC)){
			echo "<a href = 'group.html?gID=".$groups['gid']."'>";
			echo "<div class='convLink'>".$groups['g_name']."</div>";
			echo "</a></br>";
		}

	echo "</div>";
?>