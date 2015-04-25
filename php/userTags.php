<?php

if ($ownsPage || ($profileInfo["tags_visible"] && !$blockedUser)) {
    $sql = "SELECT tag_name 
			FROM (user NATURAL JOIN u_tagged) 
			WHERE (uID = '$profile')"; //put the contact in the database
    
    echo "<div id='tagsWrapper'>";
    
    if ($result = $connection->query($sql)) {
        //write out each tag
        while ($tags = $result->fetch_array(MYSQLI_ASSOC)) {
            echo "<div class='tag hvr-fade-cloud'>" . $tags["tag_name"] . "</div>";
            echo "   "; //spacing between tags		
        }
    }
    
    else {
        echo "No tags!";
    }
    //if this is their profile page, show tags
    //This now appears within the tag wrapper after all the other tags. It will only appear if you are on your own page. 	
    if ($ownsPage) {
        //form for user to tag themself
        echo "<form name='tagUser' class='tagUser' id='tagUser' method= 'POST' action='php/functions.php?tagUser=true'>";
        echo "<input type='text' name='tagName' id='tagNameInput' class='input tagName' placeholder='Add a tag'/>";
        echo "<input type='submit' name='addTag' value='Add Tag' class='button' id='tagInputButton'>";
        echo "</form>";
    }
    echo "</div>";
}
?>