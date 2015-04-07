<?php
//if this is their profile page, show tags
if($ownsPage){
	//form for user to tag themself
	echo "<form name='tagUser' class='tagUser' id='tagUser' method= 'POST' action='php/tagUser.php'>";  
		echo "<input type='text' name = 'tagName' id='tagName' class='input tagName' placeholder='Tag Name'/>";	
		echo "<input type='submit' name='addTag' value='Add Tag' class='button'>";
	echo "</form>";
}

if($ownsPage || ($profileInfo["tags_visible"] && !$blockedUser)){
	$sql = "SELECT tag_name 
			FROM (user NATURAL JOIN u_tagged) 
			WHERE (uID = '$profile')"; //put the contact in the database

	echo "<div class='tags'>";
		if($result = $connection->query($sql)){
			//write out each tag
			while($tags = $result->fetch_array(MYSQLI_ASSOC)){
				echo "<div class='tag'>".$tags["tag_name"]."</div>";
				echo "   ";//spacing between tags		
			}	
		}
		else{
			echo "No tags!";
		}
	echo "</div>";
}
?>