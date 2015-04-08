<?php
include "connect.php";
include "sessionStatus.php";

$user = $_SESSION["uID"];
$otherUser = $_GET["uID"];

$sql = "INSERT INTO conversation (cID,c_name) 
		VALUES ('0', '')";
$result = $connection->query($sql);

$cID =  mysqli_insert_id($connection); //get the id of the last inserted record

$sql = "INSERT INTO participates ($uID,$cID) 
		VALUES('$user','$cID')";
$result = $connection->query($sql);

$sql = "INSERT INTO participates ($uID,$cID) 
		VALUES('$otherUser','$cID')";
$result = $connection->query($sql);

header("Location: ../conversation.php?cID=$cID");
?>