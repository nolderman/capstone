<?php
require_once 'connect.php';
require_once 'sessionStatus.php';

//Strips the input to reduce hacking 
function test_input($data)
{
    $data = trim($data); //strip unnecessary chars
    $data = htmlspecialchars($data); //convert special characters to HTML entities
    $data = stripslashes($data); //remove backslashes
    return $data;
}

//---------------------------------------------------SEARCH--------------------------------------------------------------------------------//
if (isset($_GET['searchInput'])) {
    Search($connection);
}

function Search($connection)
{   
    $jsonArray = array(); //the json array we will be passing back
    
    $searchInput = $_GET["searchInput"];
    
    $sql = "SELECT uID,f_name, l_name 
            FROM user 
            WHERE f_name LIKE '%$searchInput%' OR l_name LIKE '%$searchInput%'";
    $result = $connection->query($sql);
    
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $array = array(); //array we are going to give back to the search
        if (isset($row["f_name"])) {
            if (isset($row["l_name"])) { //if the first name and last name were found put them both together in the search
                $array["f_name"] = $row["f_name"] . " " . $row["l_name"];
                ;
                $array["uID"] = $row["uID"];
                array_push($jsonArray, $array); //put the array of f_name and uID on the jsonArray as a single json
            } else {
                $array[] = $row["f_name"]; //just put the first name in the search
            }
        }
    }
    $jsonArray = json_encode($jsonArray);
    echo $jsonArray;
    return $jsonArray;
}

//--------------------------------------------------CREATE CONVERSATION-------------------------------------------------------------------------------//
if (isset($_GET["createConversation"])) { //only save a contact if the user put something in the submit box
    CreateConversation($connection);
}

function CreateConversation($connection)
{
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable

    $user      = $_SESSION["uID"];
    $otherUser = $_GET["uID"];
    
    $sql = "INSERT INTO conversation (cID,c_name) 
	       VALUES ('0', '')";
    $connection->query($sql);
    
    $cID = mysqli_insert_id($connection); //get the id of the last inserted record
    
    $sql = "INSERT INTO participates (uID,cID,joined) 
	       VALUES('$user','$cID','$dateTime')";
    $connection->query($sql);
    
    $sql = "INSERT INTO participates (uID,cID,joined) 
	       VALUES('$otherUser','$cID','$dateTime')";
    $connection->query($sql);

    header("Location: ../conversation.php?cID=$cID");
}

//--------------------------------------------------SEND MESSAGE IN CONVERSATION--------------------------------------------------------------------------//
//if the user clicks the submit button on the page
if (isset($_GET['sendMessage']) && isset($_POST["sendMessage"]) && isset($_POST["message"])) {
    PostMessage($connection);
}

function PostMessage($connection)
{
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
    
    $message = $_POST["message"]; //get the message that was posted 
    $uID     = $_SESSION["uID"];
    $cID     = $_GET["cID"];
    
    //insert the message into the database
    $sql    = "INSERT INTO message (uID, cID, date_time, content) 
			VALUES ('$uID', '$cID', '$dateTime', '$message')";
    $result = $connection->query($sql);
    
    header("Location: ../conversation.php?cID=$cID"); //go back to the group page
}

//---------------------------------------------------TURN CONVERSATION INTO A GROUP-----------------------------------------------------------//
if (isset($_GET["toGroup"])) {
    conversationToGroup($connection);
}

function conversationToGroup($connection)
{
    $c_name = $_POST["toGroup"];
    $cID    = $_GET["cID"];
    $uID    = $_SESSION["uID"];
    
    //make a new group
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s');
    $sql      = "INSERT INTO groups (gID, g_name, icon, visible, burn_date) VALUES ('0','$c_name', 'NULL', '1', '0000-00-00 00:00:00')";
    $result   = $connection->query(test_input($sql));
    $gID      = mysqli_insert_id($connection); //get the id of the last inserted record (in this case it is the group ID)
    
    //Put the user in the member table with this group
    $insertMember = "INSERT INTO members (uID,gID,moderator) VALUES ('$uID','$gID','1')"; //set the creator to a moderator
    $insertMember = $connection->query($insertMember);

    //Make the group own the conversation
    $sql = "INSERT INTO g_owns (gID,cID,o_participation) VALUES ('$gID', '$cID', '0')";
    $result = $connection->query($sql);
    echo $gID;
    echo"<br>";
    var_dump($cID);
    echo"<br>";
    var_dump($result);
    //get all of the participants so we can move them into members
    $sql    = "SELECT * FROM participates WHERE cID=$cID";
    $result = $connection->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) { //move all participants into members as non mods
        $uID            = $row["uID"];
        $moveToMember   = "INSERT INTO members (uID,gID,moderator) VALUES ('$uID','$gID','0')"; //put in members 
        $toMemberResult = $connection->query($moveToMember);
    }
    
    /**get all of the messages and put them in the post NOT NEEDED BUT IT WAS COOL FOR ABOUT 30 SECONDS SO I AM LEAVING IT <--- lol alright (nate)
    $sql = "SELECT * FROM message WHERE cID=$cID";
    $result = $connection->query($sql);
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
    $uID = $row["uID"];
    $content = $row["content"];
    $date_time = $row["date_time"];
    $moveToPost = "INSERT INTO post (uID,gID,date_time,content,edited) VALUES ('$uID', '$gID', '$date_time', '$content', '0')";//put in post
    $moveResult = $connection->query($moveToPost);
    }
    **/
    //header("Location: ../group.php?gID=$gID");
}


//--------------------------------------------------CREATE GROUP-------------------------------------------------------------------------------//

if (isset($_GET['createGroup']) && isset($_POST["groupName"])) { //only save a contact if the user put something in the submit box
    CreateGroup($connection);
}

function CreateGroup($connection)
{
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s');
    
    $groupName = $_POST["groupName"];
    $groupName = htmlspecialchars($groupName);
    
    //make a new group
    if (!empty($groupName)) { //make sure the user input a name for the group
        
        $groupName = addslashes($groupName);
        $sql       = "INSERT INTO groups (gID, g_name, icon, visible, burn_date) VALUES ('0','$groupName', 'NULL', '1', '0000-00-00 00:00:00')"; //put the contact in the database
        $result    = $connection->query($sql);
        
        //Put the user in the member table with this group
        $uID = $_SESSION["uID"]; //get the creator's uID
        $gID = mysqli_insert_id($connection); //get the id of the last inserted record (in this case it is the group ID)
        
        $insertMember = "INSERT INTO members (uID,gID,moderator) VALUES ('$uID','$gID','1')"; //set the creator to a moderator
        $insertMember = $connection->query($insertMember);
        
        header("Location: ../group.php?gID=$gID");
    }
    
    header("Location: ../profile.php");    
}

//--------------------------------------------------POST MESSAGE TO GROUP---------------------------------------------------------------------//
if (isset($_GET['postMessage']) && isset($_POST["postMessage"]) && isset($_POST["message"])) { //if the user clicks the submit button on the groupPage
    PostMessageToGroup($connection, $_POST["message"], $_GET['gID']);
}

function PostMessageToGroup($connection, $message, $gID)
{
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
    
    $message = addslashes($message);
    
    //Insert the message with the user who posted, group posted to, dateTime posted, and the message itself.
    $uID = $_SESSION["uID"];
    if (!empty($message)) { //only post if not empty
        $sql    = "INSERT INTO post (pID, uID, gID, date_time, content, edited) VALUES ('','$uID', '$gID', '$dateTime', '$message', '0')";
        $result = $connection->query($sql);
    }
    $pID = mysqli_insert_id($connection); //get the id of the last inserted record
    
	/*
	$getMembers = "SELECT uID 
					FROM members 
					WHERE gID = $gID";
	$result = $connection->query($getMembers);
	
		//insert into the unread table for every member
	while($members = $result->fetch_array(MYSQLI_ASSOC)){
		$uID = $members['uID'];
		$sql = "INSERT INTO postNotRead (uID,pID) VALUES ($uID, $pID)";
		$insertUnread = $connection->query($sql);
	}
	*/
	
    if (!isset($_GET['replyToPost'])) { //if we came from posting a regular message go back to the group page now
        header("Location: ../group.php?gID=$gID"); //go back to the group page
    } else
        return $pID;
}

//--------------------------------------------------REPLY TO POST------------------------------------------------------------------------//
if (isset($_GET['replyToPost'])) {
    postReply($connection);
}

function postReply($connection)
{
    
    $gID      = $_GET["gID"];
    $pID      = PostMessageToGroup($connection, $_POST["message"], $gID); //make a new post with the message and groupID and get the pID of the new reply back
    $parentID = $_GET['pID']; //take the pID that we are replying to
    echo $pID;
    
    $sql    = "INSERT INTO reply (pID, parent) VALUES ('$pID' ,'$parentID')"; //set a new reply for the parent post
    $result = $connection->query($sql);
    
    header("Location: ../group.php?gID=$gID"); //go back to the group page
}

//--------------------------------------------------EDIT POST--------------------------------------------------------------------------//
if (isset($_GET['editPost'])) {
    editPost($connection, $_POST['editPost']);
}

function editPost($connection, $newContent)
{
    $pID = $_GET['pID'];
    $gID = $_GET['gID'];
    
    $sql    = "UPDATE post SET content='$newContent', edited='true' WHERE pID=$pID";
    $result = $connection->query($sql);
    
    
    header("Location: ../group.php?gID=$gID"); //go back to the group page
}

//------------------------------------------------EDIT GROUP NAME------------------------------------------------------------------------//
if (isset($_GET['editName'])) {
    editGroupName($connection, $_POST['editName']);
}

function editGroupName($connection, $newName)
{
    $gID = $_GET['gID'];
    
    $sql    = "UPDATE groups SET g_name='$newName' WHERE gID=$gID";
    $result = $connection->query($sql);
    
    header("Location: ../group.php?gID=$gID"); //go back to the group page
}


//--------------------------------------------------DELETE GROUP-------------------------------------------------------------------------//
if (isset($_GET['deleteGroup'])) {
    deleteGroup($connection);
}

function deleteGroup($connection)
{
    $uID = $_SESSION["uID"];
    $gID = $_GET["gID"];
    echo $gID;
    echo $uID;
    $sql    = "DELETE FROM groups WHERE gID=$gID"; //delete the group 
    $result = $connection->query($sql);
    
    $sql    = "DELETE FROM post WHERE gID =$gID"; //delete all messages
    $result = $connection->query($sql);
    
    $sql    = "DELETE FROM members WHERE gID = $gID"; //delete all members
    $result = $connection->query($sql);
    
    header("Location: ../profile.php");
}

//--------------------------------------------------DELETE POST FROM GROUP-------------------------------------------------------------------------//
if (isset($_GET["deletePost"])) {
    deletePost($connection);
}

function deletePost($connection)
{
    $pID = $_GET["pID"];
    $gID = $_GET["gID"];
    
    $sql    = "DELETE FROM post WHERE pID=$pID"; //make sure to put quotes around date_time
    $result = $connection->query($sql);
    
    header("Location: ../group.php?gID=$gID");
}

//--------------------------------------------------ADD USER TO GROUP-------------------------------------------------------------------------//
if (isset($_GET["addUserToGroup"])) {
    addUserToGroup($connection);
}

function addUserToGroup($connection)
{
    
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
    $newUser  = $_POST["hiddenUID"];
    $gID      = $_GET["gID"];
    
    $sql    = "INSERT INTO members (uID,gID,moderator,joined) VALUES ($newUser, $gID, '0', $dateTime)"; //insert the user without mod permissions
    $result = $connection->query($sql);
    header("Location: ../group.php?gID=$gID");
}

//--------------------------------------------------DELETE USER FROM GROUP-------------------------------------------------------------------------//
if (isset($_GET["removeUserFromGroup"])) {
    removeUserFromGroup($connection);
}

function removeUserFromGroup($connection)
{
    $uID    = $_GET["uID"];
    $gID    = $_GET["gID"];
    $sql    = "DELETE FROM members WHERE uID=$uID AND gID=$gID";
    $result = $connection->query($sql);
    header("Location: ../group.php?gID=$gID");
}

//-----------------------------------------------BLOCK USER FROM GROUP------------------------------//
if(isset($_GET['blockUserFromGroup'])){
	blockUserFromGroup($connection);
}

function blockUserFromGroup($connection){
	
	$blockedUID = $_POST["hiddenUID"];
	$gID = $_GET['gID'];
	$sql = "INSERT INTO g_blocks (gID,uID) VALUES ($gID, $blockedUID)";
	$connection->query($sql);
	
	header("Location: ../groupSettings.php?gID=$gID");
}

//------------------------------------------------SAVE GROUP SETTINGS--------------------------------//
if(isset($_GET['saveGroupSettings'])){
	saveGroupSettings($connection);
}

function saveGroupSettings($connection){
	$gID = $_GET['gID'];
	$iconFile = $_POST['fileToUpload'];
	$visibility = $_POST['visibility'];
	echo $visibility;
	echo $iconFile;
	echo $gID;
	$sql = "UPDATE groups SET visibility=$visibility WHERE gID=$gID";//put the icon file in later
	$result = $connection->query($sql);
	header("Location: ../group.php?gID=$gID");
}

//------------------------------------------------TAG GROUP------------------------------------------//
if (isset($_GET['tagGroup'])) {
    tagGroup($connection, $_GET['gID']);
}

function tagGroup($connection, $gID)
{
    $tagName = $_POST["tagName"];
    
    //make a new tag
    $insert_tag = "INSERT INTO tag (tag_name) 
					VALUES ('$tagName')";
    $connection->query($insert_tag);
    
    //attach this user with this tag
    $insert_g_tagged = "INSERT INTO g_tagged (gID,tag_name) 
						VALUES ('$gID','$tagName')";
    $connection->query($insert_g_tagged);
    header("Location: ../group.php?gID=$gID");
}

//----------------------------------------------------DELETE GROUP TAG-----------------------------------------------//
if (isset($_GET['deleteGroupTag'])) {
    deleteGroupTag($connection, $_GET['gID'], $_GET['tag_name']);
}

function deleteGroupTag($connection, $gID, $tag_name)
{
    echo $gID;
    echo $tag_name;
    $sql    = "DELETE FROM g_tagged WHERE gID='$gID' AND tag_name='$tag_name'";
    $result = $connection->query($sql);
    header("Location: ../group.php?gID=$gID");
}

//-------------------------------------------------CHECK IF MODERATOR OF GROUP-------------------------------------------//
function groupModCheck($connection, $uID, $gID)
{
    $sql    = "SELECT moderator FROM members WHERE uID = $uID";
    $result = $connection->query($sql);
    $row    = $result->fetch_array(MYSQLI_ASSOC); //get the array with uID and pass
    
    return $moderator = $row['moderator'];
}

//--------------------------------------------------ADD CONTACT OR REMOVE CONTACT--------------------------------------------------------//
if (isset($_GET['contact'])) {
    $contact = $_GET['contact'];
    if (!$contact) {
        AddContact($connection);
    } else {
        RemoveContact($connection);
    }
}

function AddContact($connection)
{
    $dateTime  = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime  = $dateTime->format('Y-m-d H:i:s');
    //insert uID, contacts uID, and the current dateTime
    $contactID = $_GET["uID"]; //the user id of the contact
    $uID       = $_SESSION["uID"]; //the user's ID
    
    $sql    = "INSERT INTO contacts (uID, contact) VALUES ('$uID', '$contactID')"; //put the contact in the database
    $result = $connection->query($sql);
    header('Location: ../profile.php');
    
}

function RemoveContact($connection)
{
    $dateTime  = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime  = $dateTime->format('Y-m-d H:i:s');
    //insert uID, contacts uID, and the current dateTime
    $contactID = $_GET["uID"]; //the user id of the contact
    $uID       = $_SESSION["uID"]; //the user's ID
    
    $sql    = "DELETE FROM contacts WHERE uID='$uID' AND contact='$contactID'"; //Remove the contact in the database
    $result = $connection->query($sql);
    header('Location: ../profile.php');
    
}

//-------------------------------------------------BLOCK OR UNBLOCK USER------------------------------------------------------------------//
if (isset($_GET['blocked'])) {
    $blockedUser = $_GET['blocked'];
    if (!$blockedUser) {
        BlockUser($connection);
    } else {
        UnBlockUser($connection);
    }
}

function BlockUser($connection)
{
    
    //insert uID and contacts uID
    $contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
    $uID       = $_SESSION["uID"]; //the user's ID
    
    $sql    = "INSERT INTO u_blocks (uID, blocked) VALUES ('$uID', '$contactID')"; //put the block in the database
    $result = $connection->query($sql);
    header('Location: ../profile.php');
    
}

function UnBlockUser($connection)
{
    
    //insert uID and contacts uID
    $contactID = $_GET["uID"]; //the user id of the contact (passed from userButtons.php)
    $uID       = $_SESSION["uID"]; //the user's ID
    
    $sql    = "DELETE FROM u_blocks WHERE uID='$uID' AND blocked='$contactID'"; //remove the block from the database
    $result = $connection->query($sql);
    header('Location: ../profile.php');
    
}

//--------------------------------------------------TAG USER------------------------------------------------------------------------------//

if(isset($_GET['tagUser']) && isset($_POST["tagName"]) && $_POST["tagName"] != ""){
    CreateTag($connection);
}

function CreateTag($connection)
{
    $tagName = $_POST["tagName"];
    $user    = $_SESSION["uID"]; //doesn't have access to this variable because it is a separate php page
    
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
if (isset($_GET['signUp']) && isset($_POST["submit"])) {
    SignUp($connection);
}

function SignUp($connection)
{
    //Set the error variables to empty
    $firstNameErr = $lastNameErr = $emailErr = $passErr = ""; //NOT USED YET (meant for displaying user input errors on the signup page)
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (empty($_POST["firstName"])) {
            $firstNameErr = "First name is required.";
        } else {
            $firstName = test_Input($_POST["firstName"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
                $firstNameErr = "Only letters and white space allowed";
            }
        }
        
        if (empty($_POST["lastName"])) {
            $lastNameErr = "Last name is required.";
        } else {
            $lastName = test_Input($_POST["lastName"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
                $lastNameErr = "Only letters and white space allowed";
            }
        }
        
        if (empty($_POST["eMail"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_Input($_POST["eMail"]);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }
        
        if (empty($_POST["password"])) {
            $passErr = "Password is required";
        } else {
            $password = test_Input($_POST["password"]);
        }
    }
    
    $pass   = sha1($password, $raw_output = false); //encrypt their password
    //$pass = password_hash($password, PASSWORD_DEFAULT);//encrypt their password
    $sql    = "INSERT INTO user (uID,email,pass,picture,f_Name,m_name,l_Name,tags_visible,profile_visible,block_invites,block_messages) 
			VALUES ('0','$email','$pass','NULL','$firstName','NULL','$lastName','1','1','0','0')"; //make them a profile
    $result = $connection->query($sql);
    
    $uID     = mysqli_insert_id($connection); //get the id of the last inserted record
    $uIDName = "uID";
    
    $_SESSION["uID"] = $uID;
    //setcookie($uIDName, $uID, time()+60*60*24, '/');//set the user ID cookie for a day so we can get all of their information later
    header('Location: ../profile.php'); //log the user in
}

//------------------------------------------------USER LOGIN-----------------------------------------------------------------------//
if (isset($_GET['userLogin']) && isset($_POST["submit"])) { //if the Login button on index.html is set then do the logging in
    LogIn($connection);
}

function LogIn($connection)
{
    $emailname = "email"; //Constants used for cookie setting
    $passname  = "password";
    
    $email    = $_POST["usermail"];
    $password = $_POST["password"];
    $password = sha1($password, $raw_output = false); //Encrypt the password the user signed in with
    //$password = password_hash($password, PASSWORD_DEFAULT);
    $sql      = "SELECT uID, pass, f_name, l_name FROM user WHERE email = '$email '"; //ID and encrypted password of the user
    
    $result = $connection->query($sql);
    $row    = $result->fetch_array(MYSQLI_ASSOC); //get the array with uID and pass
    
    if ($password == $row["pass"]) { //compare the login password with the one they signed up with
        $_SESSION["uID"] = $row["uID"];
        
        session_write_close();
        header('Location: ../profile.php'); //go to their profile page
    } else {
        echo "Sorry you entered the wrong password";
    }
}
//-----------------------------------------------------USER LOGOUT----------------------------------------------------------------------//
if (isset($_GET['userLogout'])) {
    userLogout();
}

function userLogout()
{
    // remove all session variables
    session_unset();
    
    // destroy the session 
    session_destroy();
    
    //redirect to index page
    header('Location: ../index.php');
}
?>