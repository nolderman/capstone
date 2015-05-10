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
//---------------------------------------------------REMOVE PICTURE FROM USER--------------------------------------------------------------------------------//
if (isset($_GET["removeProfilePicture"])) {
    removeProfilePicture($connection);
}

function removeProfilePicture($connection)
{   
    $user = $_SESSION["uID"];
    $sql = "UPDATE user
            SET picture='NULL'
            WHERE uID ='$user'";
    $result = $connection->query($sql);
    header("Location: ../profile.php?");
}

//---------------------------------------------------USER SEARCH--------------------------------------------------------------------------------//
if (isset($_GET["searchInput"])) {
    Search($connection);
}

function Search($connection)
{   
    $jsonArray = array(); //the json array we will be passing back
    
    $searchInput = $_GET["searchInput"];
    $tagSearch = "SELECT uID, f_name, l_name
            FROM user NATURAL JOIN u_tagged
            WHERE (tag_name LIKE '%$searchInput%' AND profile_visible=1)";

    $userSearch = "SELECT uID, f_name, l_name
            FROM user
            WHERE ((f_name LIKE '%$searchInput%' 
                OR l_name LIKE '%$searchInput%')
                AND profile_visible=1)";

    $sql = "(".$tagSearch.") UNION (".$userSearch.")";
    $result = $connection->query($sql);
    
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $array = array(); //array we are going to give back to the search
        if (isset($row["f_name"])) {
            if (isset($row["l_name"])) { //if the first name and last name were found put them both together in the search
                $array["f_name"] = $row["f_name"] . " " . $row["l_name"];
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

//----------------------------------------------------GROUP SEARCH-------------------------------------------------------------------------------------//
if (isset($_GET["groupSearchInput"])) {
    groupSearch($connection);
}

function groupSearch($connection)
{   
    $jsonArray = array(); //the json array we will be passing back
    $searchInput = $_GET["groupSearchInput"];
    
    $tagSearch = "SELECT gID, g_name
            FROM groups NATURAL JOIN g_tagged
            WHERE (tag_name LIKE '%$searchInput%' AND visible=1)";

    $groupSearch = "SELECT gID, g_name
            FROM groups
            WHERE (g_name LIKE '%$searchInput%' AND visible=1)";

    $sql = "(".$tagSearch.") UNION (".$groupSearch.")";
    $result = $connection->query($sql);
    
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $array = array(); //array we are going to give back to the search
		
		if (isset($row["g_name"])) {
				
			//check if the user is blocked and only display if not
			$gID = $row["gID"];
			$uID = $_SESSION["uID"];
			$searchBlocked = "SELECT * FROM groups NATURAL JOIN g_blocks WHERE gID='$gID' AND uID='$uID'";
			$blockedResult = $connection->query($searchBlocked);
			$blockedRow = $blockedResult->fetch_array(MYSQLI_ASSOC);

			if(count($blockedRow)==0){//only display if the user was not blocked
				$array["g_name"] = $row["g_name"];
				$array["gID"] = $row["gID"];
				array_push($jsonArray, $array); //put the array of f_name and uID on the jsonArray as a single json
			}
		}
    }
    $jsonArray = json_encode($jsonArray);
    echo $jsonArray;
    return $jsonArray;
}


//--------------------------------------------------CREATE CONVERSATION-------------------------------------------------------------------------------//
if (isset($_GET["createConversation"])) { //only save a contact if the user put something in the submit box
    //set the convo name to "No other participants" because the base assumption is that this is a blank new conversation
    $cID = createConversation($connection, "No other participants");

    header("Location: ../conversation.php?cID=$cID");
}

function createConversation($connection, $convoName)
{
    $user = $_SESSION["uID"];

    //create a conversation between them
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
    

    //add new conversation
    $sql = "INSERT INTO conversation (cID,c_name) 
	        VALUES ('0', '$convoName')";
    $connection->query($sql);
    
    //get the ID of the conversation just created
    $cID = mysqli_insert_id($connection); 
    
    //insert this user as a participant
    $sql = "INSERT INTO participates (uID,cID,joined,unread_count) 
	        VALUES('$user','$cID','$dateTime','0')";
    $connection->query($sql);

    
    if(isset($_GET["uID"])){
        $otherUser = $_GET["uID"];

        //insert this otherUser as a participant
        $sql = "INSERT INTO participates (uID,cID,joined,unread_count) 
                VALUES('$otherUser','$cID','$dateTime','0')";
        $connection->query($sql);

         $sql  = "UPDATE conversation
                 SET c_name = ''
                 WHERE cID = '$cID'";
        $connection->query($sql);
    }
    return $cID;
}

//--------------------------------------------------SEND MESSAGE IN CONVERSATION--------------------------------------------------------------------------//
//if it was clicked from the convo page
if (isset($_GET["sendMessage"]) && isset($_POST["sendMessage"]) && isset($_POST["message"]) && !isset($_GET["gID"])) {
    
    if($_POST["message"] != ""){
        sendMessage($connection);
    }

    $cID = $_GET["cID"];
    header("Location: ../conversation.php?cID=$cID"); //go back to the conversation page
}

//if it was clicked from the group page
if(isset($_GET["sendMessage"]) && isset($_POST["sendMessage"]) && isset($_POST["message"]) && isset($_GET["gID"])) {
    
    if($_POST["message"] != ""){
        sendMessage($connection);
    }

    $gID = $_GET["gID"];
    header("Location: ../group.php?gID=$gID"); //go back to the conversation page
}


function sendMessage($connection)
{
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
    
    $message = addslashes($_POST["message"]); //get the message that was posted 
    $uID     = $_SESSION["uID"];
    $cID     = $_GET["cID"];

    //insert the message into the database
    $sql     = "INSERT INTO message(uID,cID,date_time,content) 
                VALUES('$uID','$cID','$dateTime','$message')";
    $result  = $connection->query($sql);

    $updateUnreadQuery = "UPDATE participates
                          SET unread_count = (unread_count + 1)
                          WHERE uID != '$uID' AND cID = '$cID'";
    $connection->query($updateUnreadQuery);
}

//--------------------------------------------------ADD USER TO CONVERSATION-------------------------------------------------------------------------//
if (isset($_GET["addParticipant"])) {
    addParticipant($connection);
}

function addParticipant($connection)
{   
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
    $newUser  = $_POST["hiddenUID"];
    $cID      = $_GET["cID"];

    //insert this new user into participates table
    $sql      = "INSERT INTO participates (uID,cID,joined,unread_count) 
                 VALUES ('$newUser', '$cID', '$dateTime','0')";
    $connection->query($sql);

    //if this conversation has a name indicating that it used to be with somebody else
    //and another person is invited to the conversation, update the conversation name to be blank
    $sql      = "SELECT c_name
                 FROM conversation
                 WHERE cID = '$cID' AND (c_name LIKE 'Old conversation with%' OR c_name LIKE 'No other participants')";
    $result   = $connection->query($sql);

    if($result->num_rows == 1){
        $sql  = "UPDATE conversation
                 SET c_name = ''
                 WHERE cID = '$cID'";
        $connection->query($sql);
    }

    header("Location: ../conversation.php?cID=$cID");
}


//--------------------------------------------------DELETE USER FROM CONVERSATION-------------------------------------------------------------------------//
if (isset($_GET["removeUserFromConvo"])) {
    removeUserFromConvo($connection);
}

function removeUserFromConvo($connection){
    $uID = $_SESSION["uID"];
    $cID = $_GET["cID"];
    
    //delete them out of the participates table
    $sql = "DELETE FROM participates 
            WHERE uID = $uID AND cID = $cID";
    $result = $connection->query($sql);

    //check if there are any other participants of the conversation
    $sql = "SELECT *
            FROM participates
            WHERE cID = $cID";
    $result = $connection->query($sql);

    //if there aren't any other participants, delete the conversation and all it's details from the database
    if($result->num_rows == 0){
        $sql = "DELETE FROM conversation
                WHERE cID = $cID";
        $result = $connection->query($sql);
    }
    //if there is only one user left in this conversation, change the name of the conversation to what it used to be with
    else if($result->num_rows == 1){
        //first check if it is owned by a group
        $sql = "SELECT gID
                FROM conversation NATURAL JOIN g_owns
                WHERE cID = '$cID'";
        $result = $connection->query($sql);

        //if it is owned by a group, there will be a result, otherwise feel free to rename convo
        if($result->num_rows != 0){
            $sql = "SELECT f_name
                    FROM user
                    WHERE uID = $uID";
            $result = $connection->query($sql);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $convoName = "Old conversation with ".$row["f_name"];

            $sql = "UPDATE conversation
                    SET c_name = '$convoName'
                    WHERE cID = '$cID'";
            $result = $connection->query($sql);
        }
    }
    
    header("Location: ../profile.php");
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
    $sql      = "INSERT INTO groups (gID, g_name, icon, visible, burn_date) VALUES ('0','$c_name', 'NULL', '1', '0000-00-00 00:00:00')";
    $result   = $connection->query(test_input($sql));
    $gID      = mysqli_insert_id($connection); //get the id of the last inserted record (in this case it is the group ID)
    
    //Put the user in the member table with this group
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s');
	//set the creator to a moderator and unread count to 0
    $insertMember = "INSERT INTO members (uID,gID,moderator,joined,unread_count) VALUES ('$uID','$gID','1','$dateTime','0')"; 
    $insertMember = $connection->query($insertMember);

    //Make the group own the conversation
    $sql = "INSERT INTO g_owns (gID,cID,o_participation) VALUES ('$gID', '$cID', '0')";
    $result = $connection->query($sql);

    //get all of the participants so we can move them into members
    $sql    = "SELECT * FROM participates WHERE cID=$cID";
    $result = $connection->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) { //move all participants into members as non mods
        $uID            = $row["uID"];
        $moveToMember   = "INSERT INTO members (uID,gID,moderator,joined,unread_count) VALUES ('$uID','$gID','0','$dateTime','0')"; //put in members 
        $toMemberResult = $connection->query($moveToMember);
    }
    header("Location: ../group.php?gID=$gID");
}


//--------------------------------------------------CREATE GROUP-------------------------------------------------------------------------------//

if (isset($_GET["createGroup"]) && isset($_POST["groupName"])) { //only save a contact if the user put something in the submit box
    CreateGroup($connection);
}

function CreateGroup($connection)
{
    $dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s');
    
    $groupName = $_POST["groupName"];
    $groupName = addslashes($groupName);
    
    //make a new group
    if (!empty($groupName)) { //make sure the user input a name for the group
        
        $groupName = addslashes($groupName);
        $sql       = "INSERT INTO groups (gID, g_name, icon, visible, burn_date) VALUES ('0','$groupName', 'NULL', '1', '0000-00-00 00:00:00')"; //put the group in the database
        $result    = $connection->query($sql);
        
        //Put the user in the member table with this group
        $uID = $_SESSION["uID"]; //get the creator's uID
        $gID = mysqli_insert_id($connection); //get the id of the last inserted record (in this case it is the group ID)
        
        $insertMember = "INSERT INTO members (uID,gID,moderator,joined, unread_count) VALUES ('$uID','$gID','1','$dateTime', '0')"; //set the creator to a moderator
        $connection->query($insertMember);

        //create this group's conversation
        $cID = createConversation($connection, $groupName);
        //attach the conversation to this group
        $sql = "INSERT INTO g_owns (gID,cID,o_participation)
                VALUES ('$gID','$cID','0')";
        $connection->query($sql);

        header("Location: ../group.php?gID=$gID");
    }
    
    header("Location: ../profile.php");    
}

//--------------------------------------------------POST MESSAGE TO GROUP---------------------------------------------------------------------//
if (isset($_GET["postMessage"]) && isset($_POST["postMessage"]) && isset($_POST["message"])) { //if the user clicks the submit button on the groupPage
    PostMessageToGroup($connection, $_POST["message"], $_GET["gID"]);
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
    
	
	$getMembers = "SELECT uID, unread_count
					FROM members 
					WHERE gID = $gID";
	$result = $connection->query($getMembers); //gets members and unread_count
	
	//update the number of unread posts for every member
	while($members = $result->fetch_array(MYSQLI_ASSOC)){
		$uID = $members["uID"];
		$unreadCount = $members["unread_count"] + 1;
		$sql = "UPDATE members SET unread_count=$unreadCount WHERE gID=$gID AND uID=$uID";
		$insertUnread = $connection->query($sql);
	}
	
    if (!isset($_GET['replyToPost'])) { //if we came from posting a regular message go back to the group page now
      header("Location: ../group.php?gID=$gID"); //go back to the group page
    } else
        return $pID;
}


//---------------------------------------------------MARK POST AS READ------------------------------------------------------------------//
function markAsRead($connection, $gID, $uID)
{
	//var_dump($result);
	$sql = "UPDATE members SET unread_count='0' WHERE gID=$gID AND uID=$uID";
	$deletion = $connection->query($sql);	

}

//--------------------------------------------------REPLY TO POST------------------------------------------------------------------------//
if (isset($_GET['replyToPost'])) {
    postReply($connection);
}

function postReply($connection)
{
    $gID      = $_GET["gID"];
    $pID      = PostMessageToGroup($connection, $_POST["message"], $gID); //make a new post with the message and groupID and get the pID of the new reply back
    $parentID = $_GET["pID"]; //take the pID that we are replying to
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
    
    $sql    = "UPDATE post SET content='$newContent', edited='1' WHERE pID=$pID";
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
    $newName = addSlashes($newName);
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

//------------------------------------------------DELETE REPLY FROM GROUP---------------------------------------------------------------------------//
if(isset($_GET['deleteReply'])){
	deleteReply($connection, $_GET['gID'], $_GET['pID']);
}

function deleteReply($connection, $gID, $pID){
	$pID = $_GET["pID"];
    $gID = $_GET["gID"];
    $sql    = "DELETE FROM post WHERE pID=$pID"; //make sure to put quotes around date_time
    $result = $connection->query($sql);
	
	$sql = "DELETE FROM reply WHERE pID=$pID";
	$result = $connection ->query($sql);
	
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
    
    $sql    = "INSERT INTO members (uID,gID,moderator,joined,unread_count) VALUES ('$newUser', '$gID', '0', '$dateTime', '0')"; //insert the user without mod permissions
    $result = $connection->query($sql);
	header("Location: ../group.php?gID=$gID");
}

/*--------------------------------------------------ADD PUBLIC GROUP TO USER GROUP LIST------------------------------------------------------------//
*Adds the current user to the group that they searched for in the group search bar
*@param $connection
*@param $gID the group we passed in from the search bar
*@param $uID the current session uID
*/
if(isset($_GET['addGroup'])){
	addGroup($connection, $_POST['hiddenGID'], $_SESSION['uID']);
}
function addGroup($connection, $gID, $uID){
	$dateTime = new DateTime(null, new DateTimeZone('America/Los_Angeles'));
    $dateTime = $dateTime->format('Y-m-d H:i:s'); //set the dateTime format and put it back in the variable
	
	$sql    = "INSERT INTO members (uID,gID,moderator,joined,unread_count) VALUES ('$uID', '$gID', '0', '$dateTime','0')"; //insert the user without mod permissions
    $result = $connection->query($sql);
    header("Location: ../group.php?gID=$gID");
}

//--------------------------------------------------DELETE USER PROFILE-------------------------------------------------------------------------//
if (isset($_GET["removeUserFromAll"])) {
    removeUserFromAll($connection);
}

function removeUserFromAll($connection){

    $uID = $_SESSION["uID"];
    // Should cascade down and delete everything that uses this uID
    $sql    = "DELETE FROM user WHERE uID=$uID";
    $result = $connection->query($sql);
    header("Location: ../index.php");
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
	
	$blockedUID = $_POST["blockedHiddenUID"];
	$gID = $_GET['gID'];
	$sql = "INSERT INTO g_blocks (gID,uID) VALUES ($gID, $blockedUID)";
	$connection->query($sql);

	header("Location: ../group.php?gID=$gID");

}


//-------------------------------------------------UNBLOCK USER FROM GROUP----------------------------//
if(isset($_GET['unblockUserFromGroup'])){
	unblockUserFromGroup($connection, $_GET['gID'], $_GET['uID']);
}

function unblockUserFromGroup($connection, $gID, $uID){
	$sql = "DELETE FROM g_blocks WHERE (gID='$gID' AND uID='$uID')";
	$result = $connection->query($sql);
	header("Location: ../group.php?gID=$gID");
}

//------------------------------------------------UPLOAD GROUP ICON----------------------------------//
//save the image to file and put the path in the database
function uploadGroupIcon($connection,$gID){
	
$target_dir = "../uploads/";
$target_name = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		$sql = "UPDATE groups SET icon='$target_name' WHERE gID='$gID'"; //put the path in the server
		$connection->query($sql);
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}	
}
//------------------------------------------------SAVE PROFILE SETTINGS--------------------------------//
if(isset($_GET['saveProfileSettings'])){
	saveProfileSettings($connection);
}

function saveProfileSettings($connection){
	$uID = $_SESSION['uID'];
	$tags_visible = $_POST['tags_visible'];
    $profile_visible = $_POST['profile_visible'];
	echo $tags_visible;
	echo $profile_visible;
    //set profile  visible or not (1 or 0)
	$sql = "UPDATE user SET profile_visible=$profile_visible WHERE uID=$uID";
	$result = $connection->query($sql);
    //set tags visible or not (1 or 0)
    $sql = "UPDATE user SET tags_visible=$tags_visible WHERE uID=$uID";
    $result = $connection->query($sql);

	header("Location: ../profile.php");
}

//------------------------------------------------SAVE GROUP SETTINGS--------------------------------//
if(isset($_GET['saveGroupSettings'])){
    saveGroupSettings($connection);
}

function saveGroupSettings($connection){
	$gID = $_GET['gID'];
	$visibility = $_POST['visibility'];
	
	$sql = "UPDATE groups SET visible=$visibility WHERE gID=$gID";//put the icon file in later
	$result = $connection->query($sql);
	uploadGroupIcon($connection, $gID); //save the image to file and put the path in the database

    header("Location: ../group.php?gID=$gID");
}

//------------------------------------------------TAG GROUP------------------------------------------//
if (isset($_GET['tagGroup'])) {
    tagGroup($connection, $_GET['gID']);
}

function tagGroup($connection, $gID)
{
    $tagName = addslashes($_POST["tagName"]);
    
	$tagName = addSlashes($tagName);
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
	$tag_name = addSlashes($tag_name);
    $sql    = "DELETE FROM g_tagged WHERE gID='$gID' AND tag_name='$tag_name'";
    $result = $connection->query($sql);
   header("Location: ../group.php?gID=$gID");
}

//-------------------------------------------------CHECK IF MODERATOR OF GROUP-------------------------------------------//
function groupModCheck($connection, $uID, $gID)
{
    $sql    = "SELECT moderator FROM members WHERE uID ='$uID' AND gID='$gID'";

    $result = $connection->query($sql);
    $row    = $result->fetch_array(MYSQLI_ASSOC); //get the array with uID and pass
    
    return $moderator = $row["moderator"];
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

//-------------------------------------------------BLOCK USER------------------------------------------------------------------//
if (isset($_GET['blockUser'])) {
    BlockUser($connection);
}

function BlockUser($connection)
{
    
    //insert uID and contacts uID
    $contactID = $_POST["blockedHiddenUID"]; //the user id of the contact (passed from profileSettings.php)
    $uID       = $_SESSION["uID"]; //the user's ID
    $sql    = "INSERT INTO u_blocks (uID, blocked) VALUES ('$uID', '$contactID')"; //put the block in the database
    $result = $connection->query($sql);
    
    header('Location: ../profile.php');
    
}

//-------------------------------------------------UNBLOCK USER------------------------------------------------------------------//
if (isset($_GET['unBlockUser'])) {
    UnBlockUser($connection);
}
function UnBlockUser($connection)
{
    
    //insert uID and contacts uID
    $contactID = $_GET["blockedUser"]; //the user id of the contact (passed from userButtons.php)
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
    $tagName = addslashes($_POST["tagName"]);
		
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

//----------------------------------------------------DELETE USER TAG-----------------------------------------------//
if (isset($_GET['deleteUserTag'])) {
    deleteUserTag($connection, $_GET['tag_name']);
}

function deleteUserTag($connection, $tag_name)
{
    $uID    = $_SESSION["uID"];
    $sql    = "DELETE FROM u_tagged WHERE uID='$uID' AND tag_name='$tag_name'";
    $result = $connection->query($sql);
    header("Location: ../profile.php?gID=$gID"); 
}


//----------------------------------------------USER SIGNUP-----------------------------------------------------------------------//
if (isset($_GET['signUp']) && isset($_POST["submit"])) {
    SignUp($connection);
}

function SignUp($connection)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
        if (!isset($_POST["firstName"])) {
            $registerErr = "First name is required";
        } else {
            $firstName = test_Input($_POST["firstName"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
                $registerErr = "Only letters and white space allowed in first name";
            }
        }
        
        if ($_POST["lastName"]=='') {
            $registerErr = "Last name is required.";
        } else {
            $lastName = test_Input($_POST["lastName"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
               $registerErr = "Only letters and white space allowed in last name";
            }
        }
		
        $email = test_input($_POST["eMail"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$registerErr = "Invalid email format";
		}
		
        if (empty($_POST["eMail"])) {
            $registerErr = "Email is required";
        } else {
            $email = test_Input($_POST["eMail"]);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $registerErr = "Invalid email format";
            }
        }
        
        if (empty($_POST["password"])) {
            $registerErr = "Password is required";
        } else {
            $password = test_Input($_POST["password"]);
        }
		
		if($_POST["password"] != $_POST["confirmPassword"]){
			$registerErr = "The passwords did not match";
		}
		
		if(isset($registerErr)){ //if there was an error redirect back to register
			header("Location: ../register.php?loginError=$registerErr"); 
		}else{
			$pass   = sha1($password, $raw_output = false); //encrypt their password
			//$pass = password_hash($password, PASSWORD_DEFAULT);//encrypt their password
			$sql    = "INSERT INTO user (uID,email,pass,picture,f_Name,m_name,l_Name,tags_visible,profile_visible,block_invites,block_messages) 
				VALUES ('0','$email','$pass','NULL','$firstName','NULL','$lastName','1','1','0','0')"; //make them a profile
			$result = $connection->query($sql);
	
			$uID     = mysqli_insert_id($connection); //get the id of the last inserted record
			$uIDName = "uID";
    
			$_SESSION["uID"] = $uID;
	
			header('Location: ../profile.php'); //log the user in
		}
    }
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