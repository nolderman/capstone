<?php
$sql = "SELECT tag_name 
			FROM (groups NATURAL JOIN g_tagged) 
			WHERE (gID = '$gID')"; //put the contact in the database

echo "<div id='tagsWrapper'>";

if ($result = $connection->query($sql)) {
    //write out each tag
    while ($tags = $result->fetch_array(MYSQLI_ASSOC)) {
        $tag_name = $tags["tag_name"];
        echo "<div class='tag hvr-fade-cloud'>" . $tag_name;
        if ($moderator) { //only delete tags if you are a moderator
            echo "<a href='php/functions.php?deleteGroupTag=true&gID=$gID&tag_name=$tag_name'>~X~</a>";
        }
        echo "</div>   "; //spacing between tags		
    }
} else {
    echo "No tags!";
}
//This now appears within the tag wrapper after all the other tags. 	
//form for user to tag the group
if ($isMember) {
    //do something maybe
}
echo "<form name='tagUser' class='tagUser' id='tagUser' method= 'POST' action='php/functions.php?tagGroup=true&gID=$gID'>
		<input type='text' name='tagName' id='tagNameInput' class='input tagName' placeholder='Add a tag'/>
		<input type='submit' name='addTag' value='Add Tag' class='button' id='tagInputButton'>
	</form>
</div>";

?>