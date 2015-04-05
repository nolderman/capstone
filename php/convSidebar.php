<?php
echo "<div class='sidebarHeader'>Conversations</div>";
	
//get the user's conversations
$sql = "SELECT cID, c_name 
		FROM (user NATURAL JOIN participates NATURAL JOIN conversation) 
		WHERE (uID = '$user')";

//if it isn't their profile page update query to get the conversations the user has in common with this profile
if(!$ownsPage){
	//join user's conversations with participates where the profile is participating to get the convos in common
	$sql = "SELECT cID, c_name 
			FROM (participates NATURAL JOIN ($sql) subquery0) 
			WHERE (uID = '$profile')";
}

echo "<div class='sidebarContent'>";
	if($result = $connection->query($sql)){
		//write out each conversation name to the sidebar
		while($convos = $result->fetch_array(MYSQLI_ASSOC)){
			echo "<a href = 'conversation.php?cID=".$convos['cid']."'>";
			echo "<div class='convLink hvr-fade-green'>".$convos['c_name']."</div>";
			echo "</a></br>";		
		}	
	}
	else{
		echo "<div class='convLink'>No conversations to display.</div>";
	}
echo "</div>";
?>