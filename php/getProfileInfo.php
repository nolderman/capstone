<?php

$user = $_SESSION["uID"];//will need user's uID no matter what

//set whether or not this is the user's profile page or not, and get the profile's uID
//the hiddenUID is passed from the profile page when the user clicks on a person's name 
//from the search bar and hits enter. 
if(!isset($_POST["hiddenUID"]) || $_SESSION["uID"] == $_POST["hiddenUID"]){
	$ownsPage = True;
	$profile = $user; 
}
else{
	$ownsPage = False;
	$profile = $_POST["hiddenUID"];
}

//get the info for this profile that will be needed
$sql = "SELECT uID, f_name, l_name, tags_visible, profile_visible, block_invites, block_messages 
		FROM user 
		WHERE (uID = '$profile')";
$result = $connection->query($sql);
$profileInfo = $result->fetch_array(MYSQLI_ASSOC);
?>