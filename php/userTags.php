<?php
if (is_null($otherUser) || ($profileInfo["tags_visible"] && !$blockedUser)) {
    if(is_null($otherUser)){
        $sql = "SELECT tag_name 
            FROM (user NATURAL JOIN u_tagged) 
            WHERE (uID = '$user')";
    }
    else{
        $sql = "SELECT tag_name 
            FROM (user NATURAL JOIN u_tagged) 
            WHERE (uID = '$otherUser')";
    }
    
    echo "<div id='tagsWrapper'>";
    
    if ($result = $connection->query($sql)) {
        //write out each tag
        while ($tags = $result->fetch_array(MYSQLI_ASSOC)) {
            $tag_name = $tags["tag_name"];
            echo "<div class='tag hvr-fade-cloud' id='".$tag_name."'>".$tag_name;
                echo "<a class='backdrop' onmousedown='expandTag(\"".$tag_name."\");' onmouseup='expandTag(\"".$tag_name."\");'></a>";
                echo "<a href='php/functions.php?deleteUserTag=true&tag_name=$tag_name' class='removeTagButton hvr-fade-red'>X</a>";
            echo "</div>";		
        }
    }
    
    else {
        echo "No tags!";
    }
    
    //if this is their profile page, show tags
    //This now appears within the tag wrapper after all the other tags. It will only appear if you are on your own page. 	
    if (is_null($otherUser)) {

        //form for user to tag themself
        echo "<form name='tagUser' class='tagUser' id='tagUser' method= 'POST' action='php/functions.php?tagUser=true'>";
            echo "<input type='text' name='tagName' id='tagNameInput' class='input tagName' placeholder='Add a tag'/>";
            echo "<input type='submit' name='addTag' value='Add Tag' class='button' id='tagInputButton'>";
        echo "</form>";
    }
    echo "</div>";
}
?>