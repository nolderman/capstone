<?php
$user = $_SESSION["uID"];//will need user's uID no matter what

//set whether or not this is the user's profile page or not, and get the profile's uID
if(!isset($_GET["uID"]) || $_SESSION['uID'] == $_GET['uID']){
	$ownsPage = True;
	$profile = $user; 
}
else{
	$ownsPage = False;
	$profile = $_GET['uID'];
}

//get the info for this profile that will be needed
$sql = "SELECT uID, f_name, l_name, tag_visibility, profile_visible, block_invites, block_messages 
		FROM user 
		WHERE (uID = '$profile')";
$result = $connection->query($sql);
$profileInfo = $result->fetch_array(MYSQLI_ASSOC);
?>