<?php
//this file sets up the variables the group page will need
//variables set:
//$user - the user's ID
//$gID - the group's ID
//$groupInfo - an array of associative arrays with the needed information for this group
//$members - an array of associative arrays with the needed information for the members of this group
//$isMember - boolean for whether or not the user is a member of the group
//$date_joined - date the user joined the group (if they are a member)
//$moderator - boolean for whether or not the user is a moderator
//$g_name - name of the group

//if no group is selected, redirect to profile page
if(!isset($_GET["gID"])){
	header('Location: profile.php');
}

$user = $_SESSION["uID"];
$gID = $_GET["gID"];

//get the info for this group that will be needed
$groupQuery = "SELECT gID, g_name, icon, visible
				FROM (groups)
				WHERE (gID = '$gID')";
$result = $connection->query($groupQuery);
$groupInfo = $result->fetch_array(MYSQLI_ASSOC);

//get the members of the group  (took out f_name and l_name. Did not function and I think they are not in the result)
$memberQuery = "SELECT * 
		FROM (($groupQuery) subquery0 NATURAL JOIN members)";
$result = $connection->query($memberQuery);
$members = $result->fetch_array(MYSQLI_ASSOC);
echo var_dump($members);

if(isset($members["uID"])){
	$isMember = true;
	$moderator = groupModCheck($connection, $user, $gID);//check if the current user is a moderator of the group
	$date_joined = $members["joined"];
}
else{
	$isMember = false;
	$moderator = false;
}

$g_name = $groupInfo["g_name"];
$visible = $groupInfo['visible'] == 1;//get whether or not the group is visible to the public

//if they aren't a member, and the group is set to be invisible, redirect them away from the page
if(!$isMember && !$visible){
	header('Location: profile.php');
}

?>