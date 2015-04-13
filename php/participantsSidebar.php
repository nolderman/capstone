<?php
//get the conversation ID
$cID = $_GET["cID"];

echo "<div class='sidebarHeader'>Participants</div>";

//query for the names and IDs of participants
$sql = "SELECT uID, f_name, l_name 
		FROM (participates NATURAL JOIN user) 
		WHERE (cID = '$cID')";
echo "<div class='sidebarContent'>";
	if($result = $connection->query($sql)){
		//write out each participant's name to the sidebar
		while($participants = $result->fetch_array(MYSQLI_ASSOC)){
			echo "<a href = 'profile.php?uID=".$participants["uID"]."'>";
			echo "<div class='sidebarLink profileLink hvr-fade-green'>".$participants["f_name"]." ".$participants["l_name"]."</div>";
			echo "</a></br>";		
		}	
	}
	else{
		echo "Error: There are no participants.";//this is an error because the user him/herself should be in the sidebar
	}
echo "</div>";
?>