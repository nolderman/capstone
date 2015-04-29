<?php

//if this is the user's profile page, $otherUser will be null
if(!is_null($otherUser)){
	//check if this profile is the user's contact
	$contact = True;
	$sql = "SELECT *
			FROM contacts
			WHERE (uID = '$user' AND contact = '$otherUser')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		$contact = False;
	}

	//check if this user is blocked by the profile
	$blockedUser = True;
	$sql = "SELECT *
			FROM u_blocks
			WHERE (uID = '$otherUser' AND blocked = '$user')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		$blockedUser = False;
	}

	//check if this profile is blocked by the user
	$blockedProfile = True;
	$sql = "SELECT *
			FROM u_blocks
			WHERE (uID = '$user' AND blocked = '$otherUser')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		$blockedProfile = False;
	}

	echo "<div id='profileButtonsWrapper'>";
		if(!$blockedUser){
			echo "<a href='php/functions.php?createConversation=true&uID=$otherUser' class='button profileButton hvr-fade-blue'>New Conversation</a>";
		}
		if(!$contact){
			echo "<a href='php/functions.php?uID=$otherUser&contact=$contact' class='button profileButton hvr-fade-green'>Add Contact</a>";
		}
		else{
			echo "<a href='php/functions.php?uID=$otherUser&contact=$contact' class='button profileButton hvr-fade-green'>Remove Contact</a>";
		}

	echo "</div>";
}

//write out the profile's tags
include "userTags.php";
