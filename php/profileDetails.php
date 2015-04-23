<?php
if(!$ownsPage){
	//check if this profile is the user's contact
	$contact = True;
	$sql = "SELECT *
			FROM contacts
			WHERE (uID = '$user' AND contact = '$profile')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		$contact = False;
	}

	//check if this user is blocked by the profile
	$blockedUser = True;
	$sql = "SELECT *
			FROM u_blocks
			WHERE (uID = '$profile' AND blocked = '$user')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		$blockedUser = False;
	}

	//check if this profile is blocked by the user
	$blockedProfile = True;
	$sql = "SELECT *
			FROM u_blocks
			WHERE (uID = '$user' AND blocked = '$profile')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		$blockedProfile = False;
	}

}
//if it isn't the user's profile, include the buttons
if(!$ownsPage){
	echo "<div id='profileButtonsWrapper'>";
		if(!$blockedUser){
			echo $profile;
			echo "<a href='php/functions.php?createConversation=true&uID=$profile' class='button profileButton hvr-fade-blue'>Message</a>";
		}

		if(!$contact){
			echo "<a href='php/functions.php?uID=$profile&contact=$contact' class='button profileButton hvr-fade-green'>Add Contact</a>";
		}
		else{
			echo "<a href='php/functions.php?uID=$profile&contact=$contact' class='button profileButton hvr-fade-green'>Remove Contact</a>";
		}

		if(!$blockedProfile){
			echo "<a href='php/functions.php?uID=$profile&blocked=$blockedProfile' class='button profileButton hvr-fade-red'>Block </a>";
		}
		else{
			echo "<a href='php/functions.php?uID=$profile&blocked=$blockedProfile' class='button profileButton hvr-fade-red'>Unblock </a>";
		}
	echo "</div>";
}

//write out the profile's tags
include "userTags.php";
