<?php
//this file sets up the variables the profile page will need
//Variables:
//$user - the user's ID number
//$otherUser - the ID number of the profile viewed if not the user (null if it is the user)
//$profileInfo - an associative array for the profile's information needed

$user = $_SESSION["uID"];//will need user's uID no matter what


//FIX THIS WHOLE SECTION LATER - VERY MESSY
//set whether or not this is the user's profile page or not, and get the profile's uID
//the hiddenUID is passed from the profile page when the user clicks on a person's name 
//from the search bar and hits enter. 
if((isset($_POST["hiddenUID"]) && $_SESSION["uID"] != $_POST["hiddenUID"]) || (isset($_GET["uID"]) && $_SESSION["uID"] != $_GET["uID"])){
	
	if(isset($_POST["hiddenUID"])){
		$otherUser = $_POST["hiddenUID"];
	}
	else{
		$otherUser = $_GET["uID"];
	}
	

	//get the info for this profile that will be needed
	$sql = "SELECT uID, f_name, l_name, tags_visible, profile_visible, block_invites, block_messages 
			FROM user 
			WHERE (uID = '$otherUser')";
}
else{
	$otherUser = null; 

	//get the info for this profile that will be needed
	$sql = "SELECT uID, f_name, l_name, tags_visible, profile_visible, block_invites, block_messages 
			FROM user 
			WHERE (uID = '$user')";
}


$result = $connection->query($sql);
$profileInfo = $result->fetch_array(MYSQLI_ASSOC);
?>