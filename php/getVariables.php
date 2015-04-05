<?
if(!isset($_SESSION["uID"])){
	
}

if(isset($_SESSION["uID"] && (!isset($_GET["uID"]) || $_SESSION['uID'] == $_GET['uID'])){
	$user = $_SESSION["uID"];
}
else{
	$profile = $_GET['uID'];
	$profile = $user;
}

$sql = "SELECT uID, f_name, l_name, tag_visibility, profile_visible, block_invites, block_messages 
		FROM user 
		WHERE (uID = '$profile')";
$result = $connection->query($sql);
?>