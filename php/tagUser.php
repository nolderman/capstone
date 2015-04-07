<?php
require_once 'connect.php';
require_once 'functions.php';

if(isset($_POST["tagName"])){
	CreateTag($connection);
}

function CreateTag($connection){
	//make a new tag
	$tagName = $_POST["tagName"];

	$insert_tag = "INSERT INTO tag (tag_name) VALUES ('$tagName')";
	$connection->query($insert_tag);
	
	//attach this user with this tag
	$insert_u_tagged = "INSERT INTO u_tagged (uID,tag_name) VALUES ('$uID','$tagName')";//set the creator to a moderator
	$connection->query($insert_u_tagged);
	//var_dump($connection);
	header('Location: ../profile.php');
}
?>