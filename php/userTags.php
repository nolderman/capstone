<?php

$sql = "SELECT tag_name 
		FROM (user NATURAL JOIN u_tagged) 
		WHERE (uID = '$user')"; //put the contact in the database
		
echo "<div class='tags'>";
	if($result = $connection->query($sql)){
		//write out each tag
		while($tags = $result->fetch_array(MYSQLI_ASSOC)){
			echo "<div class='tag'>".$tags['tag_name']."</div>";
			echo "   ";//spacing between tags		
		}	
	}
	else{
		echo "You have no tags!";
	}
echo "</div>";
?>