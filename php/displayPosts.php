 <?php

$sql    = "SELECT pID,uID, gID, f_name, date_time, content FROM post NATURAL JOIN user WHERE gID = '$gID' AND post.pID NOT IN (SELECT pID FROM reply) ORDER BY date_time";//add post.pid not in reply.pid
$result = $connection->query($sql); //get all of the messages

//print out the messages in an unordered list on the page
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	
    $posterID  = $row["uID"];
    $gID       = $row["gID"];
    $date_time = $row["date_time"];
    $content   = $row["content"];
	$pID = $row['pID'];	
    
   // if ($date_time > $date_joined) {
        echo "<div class='post'>";
        $content = $row['content'];
			
        echo $content;
        echo "<div class='subPost'>";
        echo $row['f_name'] . " " . $row['date_time'];
        $pID = $row['pID'];
        
        //reply to the post
        echo "<a href='' class ='reply-post groupActionLink'> Reply </a>
                                        <form class='reply-form' method='POST' action='php/functions.php?replyToPost=true&gID=$gID&pID=$pID' >
                                            <textarea cols='20' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>
                                            <input class='button' type='submit' value='Reply' />
                                        </form>";
        //delete the post link if the users' post or a moderator
        if ($posterID == $_SESSION['uID'] || $moderator) {
            echo "<a class='groupActionLink' href='php/functions.php?deletePost=true&gID=$gID&pID=$pID'>Delete Post</a>";
        }
        //edit the post    if users' post
        if ($posterID == $_SESSION['uID']) {
            echo "<a href='' id='$pID' class ='edit-post groupActionLink'> Edit Post </a>
                                        <form class='edit-form' name='editPost' method='POST' action='php/functions.php?editPost=true&gID=$gID&pID=$pID' >
                                            <textarea cols='20' rows='4' name='editPost' id='postInput'>$content</textarea>
                                            <input class='button' type='submit' value='Accept' />
                                        </form>";
        }
        echo "</div>";
        echo "</div>";
		
		$getReplies = "SELECT * FROM reply NATURAL JOIN post NATURAL JOIN user WHERE parent=$pID";//get the replies for this post
		$repliesResult = $connection->query($getReplies);
		
		while ($replies = $repliesResult->fetch_array(MYSQLI_ASSOC)) {
			echo "<div class='reply'>";
				$replyContent = $replies['content'];
				echo $replyContent;

				$replyPID = $replies['pID'];
				$replyUID = $replies['uID'];
			//delete the post link if the users' post or a moderator
			if ($replyUID == $_SESSION['uID'] || $moderator) {
				echo "<a class='groupActionLink' href='php/functions.php?deleteReply=true&gID=$gID&pID=$replyPID'> Delete Post</a>";
			}
			//edit the post    if users' post
			if ($replyUID == $_SESSION['uID']) {
				echo "<a href='' id='$pID' class ='edit-reply groupActionLink'> Edit Reply </a>
                       <form class='edit-reply-form' method='POST' action='php/functions.php?editPost=true&gID=$gID&pID=$replyPID' >
							<textarea cols='20' rows='4' name='editPost' id='postInput'> $replyContent </textarea>
							<input class='button' type='submit' value='Accept' />
						</form>";
        }
		
			echo "<div class='subPost'>";
			
			 echo $replies['f_name'] . " " . $replies['date_time'];
			
			echo "</div>";
			echo "</div>";
			
			
		}		
		
		
		
		
  //  }
}

?> 