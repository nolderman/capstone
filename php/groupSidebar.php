<?php
echo "<div class='sidebarHeader'>Groups</div>";

//get the user's groups
$sql = "SELECT gID, g_name 
		FROM (user NATURAL JOIN members NATURAL JOIN groups) 
		WHERE (uID ='$user')";

//if it isn't their profile page update query to get the groups the user has in common with this profile
if(!$ownsPage){
	//join user's groups with members and check where profile is a member to get groups in common
	$sql = "SELECT gID, g_name 
			FROM (members NATURAL JOIN ($sql) subquery0) 
			WHERE (uID = '$profile')";
}

echo "<div class='sidebarContent'>";
	if($result = $connection->query($sql)){
		if($result->num_rows == 0){//if there are no results
			echo "There are no groups to display!";
		}
		else{
			//write out each group name to the sidebar and make them links
			while($groups = $result->fetch_assoc()){
				echo "<a href = 'group.php?gID=".$groups['gID']."'>";
				echo "<div class='groupLink'>".$groups['g_name']."</div>";
				echo "</a></br>";
			}
		}
	}
echo "</div>";
?>