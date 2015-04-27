<?php

//if no group is selected, redirect to profile page
if(!isset($_GET["gID"])){
	header('Location: ../profile.php');
}

$gID = $_GET["gID"];
$uID = $_SESSION["uID"];

//get the info for this group that will be needed
$groupQuery = "SELECT gID, g_name, icon, visible
		FROM (groups)
		WHERE (gID= '$gID')";
$result = $connection->query($groupQuery);
$groupInfo = $result->fetch_array(MYSQLI_ASSOC);

//get the members of the group
$memberQuery = "SELECT uID, joined
		FROM (($groupQuery) subquery0 NATURAL JOIN members)";
$result = $connection->query($memberQuery);
$memberIDs = $result->fetch_array(MYSQLI_ASSOC);


if(isset($memberIDs['uID'])){
	$isMember = true;
	$date_joined = $memberIDs['joined'];
}else{
	$isMember = false;
}

$moderator = groupModCheck($connection, $uID, $gID);//check if the current user is a moderator of the group
$g_name = $groupInfo['g_name'];

//if they aren't a member, and the group is set to be invisible, redirect them away from the page
if(!isset($memberIDs["$uID"]) && $groupInfo["visible"] == 0){
	header('Location: ../profile.php');
}

?>