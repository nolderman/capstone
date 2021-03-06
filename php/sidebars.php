<?php
//Generates a sidebar listing the groups the user is a member of
//if on a profile page that is not the user's, the sidebar will only display groups in common with the viewed profile
//Parameters:
	//$connection - the connection to the database
	//$user - the user's ID number
	//$profile - ID number of the profile the user is viewing (if on profile page and it is not the user's)
function groupSidebar($connection, $user, $profile){
	echo "<div class='sidebarHeader'> Groups";

		//if user is viewing another user's profile, $profile won't be null
		if (!is_null($profile)) {
			echo " in Common";
		}
		echo "<form name='groupSearchBar' class='content' id='groupSearchbar' method= 'POST' action='php/functions.php?addGroup=true'>
			<input type='text' name='groupTypeahead' class='groupTypeahead' id='groupSearchbarInput' placeholder='Search'/>	
			<input type='hidden' name='hiddenGID' id='groupID' value='' />						
			<input type='submit' name='findGroup' value='Find Group!' class='hvr-fade-green button' id='searchButton' hideFocus='true'> 
		</form>";
	echo "</div>";

	
	//get the user's groups
	$sql = "SELECT gID, g_name 
			FROM (user NATURAL JOIN members NATURAL JOIN groups) 
			WHERE (uID ='$user')";

	//if user is viewing another user's profile, $profile won't be null
	//if this is the case, update query to get the groups the user has in common with this profile
	if(!is_null($profile)){
		//join user's groups with members and check where profile is a member to get groups in common
		$sql = "SELECT gID, g_name 
				FROM (members NATURAL JOIN ($sql) subquery0) 
				WHERE (uID = '$profile')";
	}

	echo "<div class='sidebarContent'>";
		if($result = $connection->query($sql)){
			//if there are no results
			if($result->num_rows == 0){
				echo "There are no groups to display!";
			}
			else{
				//write out each group name to the sidebar and make them links
				while($groups = $result->fetch_array(MYSQLI_ASSOC)){
					
					$gID = $groups['gID'];
					$uID = $_SESSION['uID'];
					$countQuery = "SELECT unread_count FROM members WHERE gID='$gID' AND uID='$uID'";
					
					$unread = $connection->query($countQuery);
					$counted = $unread->fetch_array(MYSQLI_ASSOC);
					$count = $counted['unread_count'];
					echo "<a href = 'group.php?gID=".$groups['gID']."'>";
					echo "<div class='sidebarLink groupLink hvr-fade-blue'>".$groups['g_name']."<div class='notificationBubble'>$count</div> </div>";
					echo "</a></br>";
				}
			}
		}
	echo "</div>";
}


//Generates a sidebar listing the members of a given group
//Parameters: 
	//$connection - the connection to the database
	//$user - the user's ID number
	//$gID - the group's ID number
	//$moderator - boolean of whether or not the user is a moderator of the group
	//$members - the list of members in the group (list of associative arrays)
function membersSidebar($connection, $user, $gID, $moderator, $members){
	echo "<div class='sidebarHeader'>Members</div>";

	echo "<div class='sidebarContent'>";
		//get the members of the group
		$memberQuery = "SELECT uID, gID, f_name, l_name
						FROM (members NATURAL JOIN user)
						WHERE gID = $gID";
		$result = $connection->query($memberQuery);

		//loop though each member of the group
		while($members = $result->fetch_array(MYSQLI_ASSOC)){
			$memberID = $members["uID"];
			// If you are not a moderator, any users that aren't you are not deletable.
			if ($memberID != $user && !$moderator){ 
				echo "<a href ='profile.php?uID=$memberID'>"; //clickable area without special CSS, so it takes up whole sidebarLink div.
			}

				echo "<div class='sidebarLink profileLink hvr-fade-green'>".$members["f_name"]." ".$members["l_name"];
					//If you are the user or you are a mod, you can delete this user
					if($memberID == $user || $moderator){ 
						echo "<a class='actionArea' href = 'profile.php?uID=$memberID'></a>"; //Clickable area separate from the delete tag (takes up left 80%ish of link)
						echo "<a class='deleteButton hvr-fade-red' href='php/functions.php?removeUserFromGroup=true&uID=$memberID&gID=$gID'> X </a>"; //Clickable delete button
					}
				echo "</div>";	//end profileLink	

			if ($memberID != $user && !$moderator){ 
				echo "</a>"; //close anchor, simple for the not-moderator case.
			}
		}	
	echo "</div>"; //end sidebarContent
}


//Generates a sidebar with the group's conversation
//Parameters: 
	//$connection - the connection to the database
	//$user - the user's ID number
	//$gID - the group's ID number
function groupConvoSidebar($connection, $user, $gID){
	echo "<div class='sidebarHeader'>Group Conversation</div>";

	echo "<div class='sidebarContent'>";
		$sql = "SELECT cID, joined
				FROM (g_owns NATURAL JOIN participates) 
				WHERE (gID = '$gID' AND uID = '$user')";
		$result = $connection->query($sql);
		$convo = $result->fetch_array(MYSQLI_ASSOC);
		
		$cID = $convo["cID"];
		$joined = $convo["joined"];

		include "php/displayMessages.php";

		echo "<div id='messageInputWrapper'>";
			echo "<form name='sendMessage' method='POST' action='php/functions.php?sendMessage=true&cID=$cID&gID=$gID'>";
			echo "<textarea cols='50' rows='4' name='message' id='messageInput' placeholder='Type your message to the group here!'></textarea>";     
			echo "<input type='submit' name='sendMessage' value='Send Message' class='button hvr-fade-green' id='sendButton'>";				
			echo "</form>";
		echo "</div>";

	echo "</div>";
}


//Generates a sidebar listing the conversations the user participates in
//if on a profile page that is not the user's, the sidebar will only display conversations in common with the viewed profile
//Parameters:
	// $connection - the connection to the database
	// $user - the user's ID number
	// $profile - ID number of the profile the user is viewing (if on profile page and it is not the user's)
//ways to make this more efficient to consider later: 
	//should change method of naming conversation to name of members (consider including the user's name???)
	//counter in database for unread messages instead of existence in database indicating unread, table could have uID,cID,counter
function conversationSidebar($connection, $user, $profile){
	echo "<div class='sidebarHeader'>Conversations";
		//if user is viewing another user's profile, $profile won't be null
		if (!is_null($profile)) {
			echo " in Common";
		}
	echo "</div>";

	//get the user's conversations
	$sql = "SELECT cID, c_name
			FROM (user NATURAL JOIN participates NATURAL JOIN conversation) 
			WHERE (uID = '$user')";

	//if user is viewing another user's profile, $profile won't be null
	//if this is the case, update query to get the conversations the user has in common with this profile
	if (!is_null($profile)) {
	    //join user's conversations with participates where the profile is participating to get the convos in common
	    $sql = "SELECT cID, c_name 
				FROM (participates NATURAL JOIN ($sql) subquery0) 
				WHERE (uID = '$profile')";
	}

	echo "<div class='sidebarContent'>";
		if ($result = $connection->query($sql)) {

		    //if there are no results
		    if ($result->num_rows == 0) {
		        echo "There are no conversations to display";
		    } 
		    else{
		        //write out each conversation to the sidebar
		        while ($convos = $result->fetch_array(MYSQLI_ASSOC)){
		            $cID = $convos["cID"];

		            echo "<a href = 'conversation.php?cID=$cID'>";
		            echo "<div class='sidebarLink convLink hvr-fade-green'>";
			            //if there is no conversation name, make the conversation name the names of all the participants except the user
			            if ($convos["c_name"] == "") {
			                $nameQuery = "SELECT f_name
										  FROM (user NATURAL JOIN participates)
										  WHERE (uID <> '$user' AND cID = '$cID')";
			                $nameResult   = $connection->query($nameQuery);
			                $allNames = "";
			                
			                //go through all the names, adding to the string
			                while ($names = $nameResult->fetch_array(MYSQLI_ASSOC)) {
			                    $allNames = $allNames . ", " . $names["f_name"];
			                }
			                
			                $allNames = subStr($allNames, 2); //take out the leading comma
			                echo $allNames;
			            } else {
			                echo $convos["c_name"];
			            }

			            //fill out how many unread messages there are for this conversation
			            echo "<div class='notificationBubble'>";
			            	$unreadQuery = "SELECT unread_count
			            					FROM participates
			            					WHERE uID = '$user' AND cID = '$cID'";
			            	$unreadResult = $connection->query($unreadQuery);
			            	$unread = $unreadResult->fetch_array(MYSQLI_ASSOC);
			                echo $unread["unread_count"];
			        	echo "</div>";
		            echo "</div></a>";
		        }
		    }
		}
	echo "</div>";
}


//Generates a sidebar listing the participants in a given conversation
//Parameters:
	//$connection - the connection to the database
	//$cID - the ID number of the conversation
function participantSidebar($connection, $cID, $user){
	echo "<div class='sidebarHeader'>Participants</div>";

	//query for the names and IDs of participants
	$sql = "SELECT uID, f_name, l_name 
			FROM (participates NATURAL JOIN user) 
			WHERE (cID = '$cID')";

	echo "<div class='sidebarContent'>";
		if($result = $connection->query($sql)){
			//write out each participant's name to the sidebar
			while($participants = $result->fetch_array(MYSQLI_ASSOC)){
				// it is not you, do the normal anchor tag without any special delete button CS

				if($participants["uID"] != $user){ 
				echo "<a href = 'profile.php?uID=".$participants["uID"]."'>";
				}
				echo "<div class='sidebarLink profileLink hvr-fade-green'>".$participants["f_name"]." ".$participants["l_name"];
				if($participants["uID"] == $user){ 
					echo "<a class='actionArea' href = 'profile.php?uID=".$participants["uID"]."'></a>";
					echo "<a class='deleteButton hvr-fade-red' href='php/functions.php?removeUserFromConvo=true&cID=$cID'>X</a>";
				}
				echo "</div>";		
				if($participants["uID"] != $user){ 
				echo "</a>";
				}
			}	
		}
		else{
			echo "Error: There are no participants.";//this is an error because at minimum, the user should be in the sidebar
		}
	echo "</div>";
}

?>