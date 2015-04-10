<?php		
$sql = "SELECT f_name, date_time, content 
		FROM (message NATURAL JOIN user)
		WHERE (cID = '$cID')
		ORDER BY date_time";

$result = $connection->query($sql);//get all of the messages

//if there are any messages, print them out
if($result->num_rows > 0){
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		echo "<div class='message'>";		
		echo $row["f_name"].": ".$row["content"];
		echo "</div>";
	}	
}		
?>