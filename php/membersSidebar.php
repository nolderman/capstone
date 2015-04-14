<?php
echo "<div class='sidebarHeader'>Members</div>";

//query for the names and IDs of members
$sql = "SELECT uID, f_name, l_name FROM (($memberQuery) subquery0 NATURAL JOIN user)";
echo "<div class='sidebarContent'>";
	if($result = $connection->query($sql)){
		//write out each member's name to the sidebar
		while($members = $result->fetch_array(MYSQLI_ASSOC)){
			$uID = $members["uID"];
			echo "<a href = 'profile.php?uID=".$members["uID"]."'>";
			echo "<div class='sidebarLink profileLink hvr-fade-green'>".$members["f_name"]." ".$members["l_name"];
			echo "<a href='php/functions.php?removeUserFromGroup=true&uID=$uID&gID=$gID'> ~Remove~ </a></div>";
			echo "</a></br>";		
		}	
	}
	else{
		echo "Error: There are no participants.";//this is an error because the user him/herself should be in the sidebar
	}
echo "</div>";

?>