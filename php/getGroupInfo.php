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
$memberQuery = "SELECT uID
		FROM (($groupQuery) subquery0 NATURAL JOIN members)";
$result = $connection->query($memberQuery);
$memberIDs = $result->fetch_array(MYSQLI_ASSOC);


?>