<?php
if(isset($_SESSION["uID"])){
	$user = $_SESSION["uID"];

	echo "<div class='sidebarHeader'>Groups</div>";
	
	//get the user's groups
	$sql = "SELECT gID, g_name 
			FROM (user NATURAL JOIN members NATURAL JOIN groups) 
			WHERE (uID ='$user')";

	//if it isn't their profile page update query to get the groups the user has in common with this profile
	if(isset($_GET['uID']) && $user <> $_GET['uID']){
		$profile = $_GET["uID"];
		//join user's groups with members and check where profile is a member to get groups in common
		$sql = "SELECT gID, g_name 
				FROM (members NATURAL JOIN '$sql') 
				WHERE (uID = '$profile')";
	}

	echo "<div class='sidebarContent'>";
		if($result = $connection->query($sql)){
			
			//write out each group name to the sidebar and make them links
			while($groups = $result->fetch_assoc()){
				echo "<a href = 'group.php?gID=".$groups['gID']."'>";
				echo "<div class='groupLink'>".$groups['g_name']."</div>";
				echo "</a></br>";
			}
		}
		else{
			echo "<div class='groupLink'>You have no groups!</div>";
		}
	echo "</div>";
}
?>