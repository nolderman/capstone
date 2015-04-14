<?php
require_once 'connect.php';
require_once 'sessionStatus.php';

//Strips the input to reduce hacking 
function test_input($data) {
  $data = trim($data);  //strip unnecessary chars
  $data = stripslashes($data); //remove backslashes
  $data = htmlspecialchars($data);//convert special characters to HTML entities
  return $data;
}

//---------------------------------------------------SEARCH--------------------------------------------------------------------------------//
if(isset($_GET['searchInput'])){
	Search($connection);
}
function Search($connection){
	
	$jsonArray = array();//the json array we will be passing back
	
	$searchInput = $_GET["searchInput"];
	
	$sql = "SELECT uID,f_name, l_name FROM user WHERE f_name LIKE '%$searchInput%' OR l_name LIKE '%$searchInput%'";
	$result= $connection->query($sql);
	
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
	$array = array(); //array we are going to give back to the search
		if(isset($row["f_name"])){
			if(isset($row["l_name"])){ //if the first name and last name were found put them both together in the search
				$array["f_name"] =  $row["f_name"]." ".$row["l_name"];;
				$array["uID"] = $row["uID"];
				array_push($jsonArray, $array);//put the array of f_name and uID on the jsonArray as a single json
			}else{
				$array[] = $row["f_name"]; //just put the first name in the search
			}
		}
	}
	$jsonArray = json_encode($jsonArray);
	echo $jsonArray;
	return $jsonArray;
}
//--------------------------------------------------CREATE CONVERSATION-------------------------------------------------------------------------------//
if(isset($_GET['createConversation'])){//only save a contact if the user put something in the submit box
	CreateConversation($connection);
}
function CreateConversation($connection){
$user = $_SESSION["uID"];
$otherUser = $_GET["uID"];

$sql = "INSERT INTO conversation (cID,c_name) 
		VALUES ('0', '')";
$connection->query($sql);

$cID =  mysqli_insert_id($connection); //get the id of the last inserted record

$sql = "INSERT INTO participates (uID,cID) 
		VALUES('$user','$cID')";
$connection->query($sql);

$sql = "INSERT INTO participates (uID,cID) 
		VALUES('$otherUser','$cID')";
$connection->query($sql);

header("Location: ../conversation.php?cID=$cID");
}
//--------------------------------------------------SEND MESSAGE IN CONVERSATION--------------------------------------------------------------------------//
//if the user clicks the submit button on the page
if(isset($_GET['sendMessage']) && isset($_POST["sendMessage"]) && isset($_POST["message"])){ 
	PostMessage($connection);
}

function PostMessage($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$message = $_POST["message"]; //get the message that was posted 
	$uID = $_SESSION["uID"];
	$cID = $_GET["cID"];
	
	//insert the message into the database
	$sql = "INSERT INTO message (uID, cID, date_time, content) 
			VALUES ('$uID', '$cID', '$dateTime', '$message')";
	$result = $connection->query($sql);
	
	header("Location: ../conversation.php?cID=$cID"); //go back to the group page
}

//--------------------------------------------------CREATE GROUP-------------------------------------------------------------------------------//

if(isset($_GET['createGroup']) && isset($_POST["groupName"])){//only save a contact if the user put something in the submit box
	CreateGroup($connection);
}

function CreateGroup($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s');

	$groupName = $_POST["groupName"];

	//make a new group
	$sql = "INSERT INTO groups (gID, g_name, icon, visible, burn_date) VALUES ('0','$groupName', 'NULL', '1', '0000-00-00 00:00:00')"; //put the contact in the database
	$result = $connection->query($sql);

	//Put the user in the member table with this group
	$uID = $_SESSION["uID"]; 				//get the creator's uID
	$gID =  mysqli_insert_id($connection); //get the id of the last inserted record (in this case it is the group ID)

	$insertMember = "INSERT INTO members (uID,gID,moderator) VALUES ('$uID','$gID','1')";//set the creator to a moderator
	$insertMember = $connection->query($insertMember);
	
	header("Location: ../group.php?gID=$gID");
}
//--------------------------------------------------POST MESSAGE TO GROUP---------------------------------------------------------------------//
if(isset($_GET['postMessage']) && isset($_POST["postMessage"]) && isset($_POST["message"])){ //if the user clicks the submit button on the groupPage
	PostMessageToGroup($connection);
}
function PostMessageToGroup($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$message = $_POST["message"]; //get the message that was posted 
	
	//Insert the message with the user who posted, group posted to, dateTime posted, and the message itself.
	$uID = $_SESSION["uID"];
	$gID = $_GET["gID"];
	
	$sql = "INSERT INTO post (uID, gID, date_time, content, edited) VALUES ('$uID', '$gID', '$dateTime', '$message', '0')";
	$result = $connection->query($sql);
	
	header("Location: ../group.php?gID=$gID"); //go back to the group page
}

//--------------------------------------------------DELETE GROUP-------------------------------------------------------------------------//

if(isset($_GET['deleteGroup'])){
	deleteGroup($connection);
}
function deleteGroup($connection){
	$uID = $_SESSION["uID"];
	$gID = $_GET["gID"];
	echo $gID;
	echo $uID;
	$sql = "DELETE FROM groups WHERE gID=$gID"; //delete the group 
	$result = $connection->query($sql);
	
	$sql = "DELETE FROM post WHERE gID =$gID";//delete all messages
	$result = $connection->query($sql);

	$sql = "DELETE FROM members WHERE gID = $gID";//delete all members
	$result = $connection->query($sql);
	
	header("Location: ../profile.php");
}
//--------------------------------------------------DELETE POST FROM GROUP-------------------------------------------------------------------------//
if(isset($_GET["deletePost"])){
	deletePost($connection);
}
function deletePost($connection){
	$uID = $_GET["uID"];
	$gID = $_GET["gID"];
	$date_time = $_GET["date_time"];
	$sql = "DELETE FROM post WHERE uID=$uID AND gID=$gID AND date_time='$date_time'";//make sure to put quotes around date_time
	$result = $connection->query($sql);
	
	header("Location: ../group.php?gID=$gID");
}
//--------------------------------------------------ADD USER TO GROUP-------------------------------------------------------------------------//
if(isset($_GET["addUserToGroup"])){
	addUserToGroup($connection);
}
function addUserToGroup($connection){
	$newUser = $_POST["hiddenUID"];
	$gID = $_GET["gID"];
	$sql = "INSERT INTO members (uID,gID,moderator) VALUES ($newUser, $gID, '0')"; //insert the user without mod permissions
	$result = $connection ->query($sql);
	header("Location: ../group.php?gID=$gID");
}

//--------------------------------------------------DELETE USER FROM GROUP-------------------------------------------------------------------------//
if(isset($_GET["removeUserFromGroup"])){
	removeUserFromGroup($connection);
}
function removeUserFromGroup($connection){
	$uID = $_GET["uID"];
	$gID = $_GET["gID"];
	$sql = "DELETE FROM members WHERE uID=$uID AND gID=$gID";
	$result = $connection->query($sql);
	
	header("Location: ../group.php?gID=$gID");
}


//--------------------------------------------------ADD CONTACT OR REMOVE CONTACT--------------------------------------------------------//
if(isset($_GET['contact'])){
		$contact = $_GET['contact'];
		if(!$contact){
		AddContact($connection);
	}else{
		RemoveContact($connection);
	}
}
function AddContact($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	//insert uID, contacts uID, and the current dateTime
	$contactID = $_GET["uID"]; //the user id of the contact
	$uID = $_SESSION["uID"];//the user's ID
	echo $uID;
	echo $contactID;

	$sql = "INSERT INTO contacts (uID, contact) VALUES ('$uID', '$contactID')"; //put the contact in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}

function RemoveContact($connection){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
	$dateTime = $dateTime->format('Y-m-d H:i:s');
	//insert uID, contacts uID, and the current dateTime
	$contactID = $_GET["uID"]; //the user id of the contact
	$uID = $_SESSION["uID"];//the user's ID
	echo $uID;
	echo $contactID;

	$sql = "DELETE FROM contacts WHERE uID='$uID' AND contact='$contactID'"; //Remove the contact in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}

//-------------------------------------------------BLOCK OR UNBLOCK USER------------------------------------------------------------------//
if(isset($_GET['blocked'])){
	$blockedUser = $_GET['blocked'];
	if(!$blockedUser){
		BlockUser($connection);
	}else{
		UnBlockUser($connection);
	}
}
function BlockUser($connection){

	//insert uID and contacts uID
	$contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
	$uID = $_SESSION["uID"];//the user's ID
	
	$sql = "INSERT INTO u_blocks (uID, blocked) VALUES ('$uID', '$contactID')"; //put the block in the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}	
function UnBlockUser($connection){

	//insert uID and contacts uID
	$contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
	$uID = $_SESSION["uID"];//the user's ID
	
	$sql = "DELETE FROM u_blocks WHERE uID='$uID' AND blocked='$contactID'"; //remove the block from the database
	$result = $connection->query($sql);
	header('Location: ../profile.php');

}

//--------------------------------------------------TAG USER------------------------------------------------------------------------------//
if(isset($_GET['tagUser']) && isset($_POST["tagName"])){
	CreateTag($connection);
}

function CreateTag($connection){
	$tagName = $_POST["tagName"];
	$user = $_SESSION["uID"];//doesn't have access to this variable because it is a separate php page

	//make a new tag
	$insert_tag = "INSERT INTO tag (tag_name) 
					VALUES ('$tagName')";
	$connection->query($insert_tag);
	
	//attach this user with this tag
	$insert_u_tagged = "INSERT INTO u_tagged (uID,tag_name) 
						VALUES ('$user','$tagName')";
	$connection->query($insert_u_tagged);
	
	header('Location: ../profile.php');
}


//----------------------------------------------USER SIGNUP-----------------------------------------------------------------------//
if(isset($_GET['signUp']) && isset($_POST["submit"])){
	SignUp($connection);
}

function SignUp($connection){
	//Set the error variables to empty
	$firstNameErr = $lastNameErr = $emailErr = $passErr = ""; //NOT USED YET (meant for displaying user input errors on the signup page)
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		if(empty($_POST["firstName"])){
			$firstNameErr = "First name is required.";
		} else{ 
			$firstName = test_Input($_POST["firstName"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
				$firstNameErr = "Only letters and white space allowed";
			}
		}
	
		if(empty($_POST["lastName"])){
			$lastNameErr = "Last name is required.";
		}else{
			$lastName = test_Input($_POST["lastName"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
				$lastNameErr = "Only letters and white space allowed";
			}
		}
		
		if(empty($_POST["eMail"])){
			$emailErr = "Email is required";
		} else{
			$email = test_Input($_POST["eMail"]);
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format";
			}
		}
	
		if(empty($_POST["password"])){
			$passErr = "Password is required";
		}
		else{
			$password =  test_Input($_POST["password"]);
		}
	}

	$pass = sha1($password, $raw_output = false); //encrypt their password
	//$pass = password_hash($password, PASSWORD_DEFAULT);//encrypt their password
	$sql= "INSERT INTO user (uID,email,pass,picture,f_Name,m_name,l_Name,tags_visible,profile_visible,block_invites,block_messages) 
			VALUES ('0','$email','$pass','NULL','$firstName','NULL','$lastName','1','1','0','0')"; //make them a profile
	$result = $connection->query($sql);
	
	$uID =  mysqli_insert_id($connection); //get the id of the last inserted record
	$uIDName = "uID";
	
	$_SESSION["uID"] = $uID;
	//setcookie($uIDName, $uID, time()+60*60*24, '/');//set the user ID cookie for a day so we can get all of their information later
	header('Location: ../profile.php');		//log the user in
}

//------------------------------------------------USER LOGIN-----------------------------------------------------------------------//
if(isset($_GET['userLogin']) && isset($_POST["submit"])){ //if the Login button on index.html is set then do the logging in
		LogIn($connection);
}
function LogIn($connection) {
	$emailname = "email"; //Constants used for cookie setting
	$passname = "password";
	
	$email = $_POST["usermail"];
	$password = $_POST["password"];
	$password = sha1($password, $raw_output = false); //Encrypt the password the user signed in with
	//$password = password_hash($password, PASSWORD_DEFAULT);
	$sql = "SELECT uID, pass, f_name, l_name FROM user WHERE email = '$email '";//ID and encrypted password of the user

	$result = $connection->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC); //get the array with uID and pass
	
	if($password == $row["pass"]){//compare the login password with the one they signed up with
		$_SESSION["uID"] = $row["uID"];
		
		session_write_close();
		header('Location: ../profile.php'); //go to their profile page
	} else{
		echo "Sorry you entered the wrong password";
	}
}
//-----------------------------------------------------USER LOGOUT----------------------------------------------------------------------//
if(isset($_GET['userLogout'])){
	userLogout();
}
function userLogout(){
	// remove all session variables
	session_unset(); 

	// destroy the session 
	session_destroy(); 

	//redirect to index page
	header('Location: ../index.php');
}
?>