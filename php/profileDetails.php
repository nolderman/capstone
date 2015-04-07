<?php
if(!$ownsPage){
	//check if this profile is the user's contact
	$contact = False;
	$sql = "SELECT *
		FROM contacts
		WHERE (uID = '$user' AND contact = '$profile')";
	if($result = $connection->query($sql)){
		$contact = True;
	}

	//check if this profile is blocked by the user
	$blocked = False;
	$sql = "SELECT *
		FROM u_blocks
		WHERE (uID = '$user' AND blocked = '$profile')";
	if($result = $connection->query($sql)){
		$blocked = True;
	}
}



?>