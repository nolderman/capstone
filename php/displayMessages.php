<?php		

$user = $_SESSION["uID"]; //for checking if it is ours or another's message.

$sql = "SELECT uID, picture, f_name, date_time, content 
		FROM (message NATURAL JOIN user)
		WHERE (cID = '$cID' AND date_time > '$joined')
		ORDER BY date_time";

$result = $connection->query($sql);//get all of the messages

$basename = substr(strtolower(basename($_SERVER['PHP_SELF'])), 0, strlen(basename($_SERVER['PHP_SELF'])) - 4);

//if there are any messages, print them out
if($result->num_rows > 0){
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if ($basename == 'group') { // if we are on the group page
			echo "<div class='groupMessageWrapper'>";
			 	
				//Image of the user. change this to profile image when that is implemented
				echo"<div class='image'>";
					//If the string is "NULL" (aka, no picture in the database for this person) then upload a silhouette instead.
					if (($row['picture']) == 'NULL'){
						echo "<img class = 'image' src='images/silhouette.jpg'>";
					}
					else{
						echo "<img class = 'image' src='uploads/profile_images/".$row['picture']."''>";
					}
				echo"</div>";
				//actual message content, within a chat bubble.
				echo "<div class='message'>";		
					echo $row["f_name"].": ".$row["content"];
				echo "</div>";

			echo "</div>";
		}

		if ($basename != 'group') { // Else, we are on the conversation page, and need different CSS for us and others 
			if($row['uID'] == $user){ // If the message is from you
				echo "<div class='selfMessageWrapper'>";
				 	
					//Image of the user. change this to profile image when that is implemented
					echo"<div class='image'>";
						//If the string is "NULL" (aka, no picture in the database for this person) then upload a silhouette instead.
						if (($row['picture']) == 'NULL'){
							echo "<img class = 'image' src='images/silhouette.jpg'>";
						}
						else{
							echo "<img class = 'image' src='uploads/profile_images/".$row['picture']."''>";
						}
					echo"</div>";
					//actual message content, within a chat bubble.
					echo "<div class='message'>";		
						echo $row["f_name"].": ".$row["content"];
					echo "</div>";

				echo "</div>";
			}

			else { // If the message is from another user.
				echo "<div class='otherMessageWrapper'>";
					
					//Image of the user. change this to profile image when that is implemented
					echo"<div class='image'>";
									//If the string is "NULL" (aka, no picture in the database for this person) then upload a silhouette instead.
						if (($row['picture']) == 'NULL'){
							echo "<img class = 'image' src='images/silhouette.jpg'>";
						}
						else{
							echo "<img class = 'image' src='uploads/profile_images/".$row['picture']."''>";
						}
					echo"</div>";

					//actual message content, within a chat bubble.
					echo "<div class='message'>";		
						echo $row["f_name"].": ".$row["content"];
					echo "</div>";

				echo "</div>";
			}
		}
	}	
}		
?>