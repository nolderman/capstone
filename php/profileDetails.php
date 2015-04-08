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

//write out the profile's tags
include "userTags.php";

//if it isn't the user's profile, include the buttons
if(!ownsPage){
	if(!$blockedUser){
		echo "<div class='button'>Message </div> <br>";
	}

	if(!$contact){
		echo "<a href='addContact.php?uID=$profile' class='button'>Add Contact</a> <br>";
	}

	if(!$blockedProfile){
		echo "<a href='blockUser.php?uID=$profile' class='button'>Block </a> <br>";
	}
}
