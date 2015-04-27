<?php
echo "<div class='sidebarHeader'>Conversations</div>";

//get the user's conversations
$sql = "SELECT cID, c_name
		FROM (user NATURAL JOIN participates NATURAL JOIN conversation) 
		WHERE (uID = '$user')";
//if($result = $connection->query($sql)){	
//	$result = $connection->query($sql);
//	while($convos = $result->fetch_array(MYSQLI_ASSOC)){
//		var_dump($convos);
//	}
//	}
//if it isn't their profile page update query to get the conversations the user has in common with this profile
if (!$ownsPage) {
    //join user's conversations with participates where the profile is participating to get the convos in common
    $sql = "SELECT cID, c_name 
			FROM (participates NATURAL JOIN ($sql) subquery0) 
			WHERE (uID = '$profile')";
}

echo "<div class='sidebarContent'>";

if ($result = $connection->query($sql)) {
    
    if ($result->num_rows == 0) { //if there are no results
        echo "There are no conversations to display";
    } else {
        //write out each conversation name to the sidebar
        while ($convos = $result->fetch_array(MYSQLI_ASSOC)) {
            //var_dump($convos);
            $cID = $convos["cID"];
            echo "<a href = 'conversation.php?cID=$cID'>";
            
            //if there is no conversation name, make the conversation name the names of all the participants except the user
            if ($convos["c_name"] == "") {
                $sql      = "SELECT f_name
							FROM (user NATURAL JOIN participates)
							WHERE (uID <> '$user' AND cID = '$cID')";
                $result   = $connection->query($sql);
                $allNames = "";
                
                //go through all the names, adding to the string
                while ($names = $result->fetch_array(MYSQLI_ASSOC)) {
                    $allNames = $allNames . ", " . $names["f_name"];
                }
                
                $allNames = subStr($allNames, 2); //take out the leading comma
                echo "<div class='sidebarLink convLink hvr-fade-green'>" . $allNames . "</div>";
            } else {
                echo "<div class='convLink hvr-fade-green'>" . $convos["c_name"] . "</div>";
            }
            
            echo "</a></br>";
        }
    }
}
echo "</div>";
?>