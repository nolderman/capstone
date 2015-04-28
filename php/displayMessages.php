<?php		
$sql = "SELECT f_name, date_time, content 
		FROM (message NATURAL JOIN user)
		WHERE (cID = '$cID' AND date_time > '$joined')
		ORDER BY date_time";

$result = $connection->query($sql);//get all of the messages

//if there are any messages, print them out
if($result->num_rows > 0){
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		echo "<li class='selfMessageWrapper'>";
			//Image of the user. change this to profile image when that is implemented
			echo"<div class='image'>";
				echo"<img src='images/silhouette.jpg'>";
			echo"</div>";

			//actual message content, within a chat bubble.
			echo "<div class='triangle-right message'>";		
			echo $row["f_name"].": ".$row["content"];
			echo "</div>";

		echo "</li>";
	}	
}		
?>