<?php
//if no conversation is set, redirect to profile page
if(!isset($_GET["cID"])){
	header('Location: ../profile.php');
}

$user = $_SESSION["uID"];
$ownsPage = True;//so the conversation sidebar doesn't try to find convos in common with anyone

// $cID = $_GET["cID"];
// $uID = $_SESSION["uID"];

// //get the info for this group that will be needed
// $groupQuery = "SELECT gID, g_name, icon, visible
// 		FROM (groups)
// 		WHERE (gID= '$gID')";
// $result = $connection->query($groupQuery);
// $groupInfo = $result->fetch_array(MYSQLI_ASSOC);

// //get the members of the group
// $memberQuery = "SELECT uID
// 		FROM (($groupQuery) subquery0 NATURAL JOIN members)";
// $result = $connection->query($memberQuery);
// $memberIDs = $result->fetch_array(MYSQLI_ASSOC);

// //if they aren't a member, and the group is set to be invisible, redirect them away from the page
// if(!isset($memberIDs["$uID"]) && $groupInfo["visible"] == 0){
// 	header('Location: profile.php');
// }

?>