<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/sidebars.php';
	require_once 'php/functions.php';

	//the following code sets up these variables for the page's use:
	//$user - the user's ID number
	//$otherUser - the ID number of the profile viewed if not the user (null if it is the user)
	//$profileInfo - an associative array for the profile's information needed

	$user = $_SESSION["uID"];//will need user's uID no matter what

	if((isset($_POST["hiddenUID"]) && $_SESSION["uID"] != $_POST["hiddenUID"]) || (isset($_GET["uID"]) && $_SESSION["uID"] != $_GET["uID"])){
		
		if(isset($_POST["hiddenUID"])){
			$otherUser = $_POST["hiddenUID"];
		}
		else{
			$otherUser = $_GET["uID"];
		}

		//get the info for this profile that will be needed
		$sql = "SELECT uID, f_name, l_name, picture, tags_visible, profile_visible, block_invites, block_messages 
				FROM user 
				WHERE (uID = '$otherUser')";
	}
	else{
		$otherUser = null; 

		//get the info for this profile that will be needed
		$sql = "SELECT uID, f_name, l_name, picture, tags_visible, profile_visible, block_invites, block_messages 
				FROM user 
				WHERE (uID = '$user')";
	}

	$result = $connection->query($sql);
	$profileInfo = $result->fetch_array(MYSQLI_ASSOC);

	$tags_visible = $profileInfo['tags_visible'] == 1;//get whether or not the profile's tags are visible to other users
	$profile_visible = $profileInfo['profile_visible'] == 1;//get whether or not the profile is visible to other users
?>


<!DOCTYPE html>
<HTML5>
	<head>
		<title>Profile Page</title>
		<link href="css/profile.css" rel="stylesheet" media="all">
		<link href="css/buttons.css" rel="stylesheet" media="all">
		<link href="css/chatWindows.css" rel="stylesheet" type="text/css">
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for general styling of the right left and middle columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/tags.css" rel="stylesheet" type="text/css"> <!-- CSS file for tags -->
		<link href="css/notifications.css" rel="stylesheet" type="text/css"> <!-- CSS file for notifications -->
		<link href="css/settings.css" rel="stylesheet" type="text/css"> <!-- CSS file for settings menu -->

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		
		<!-- searching javascript codes -->
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" src="javascript/search.js"></script>  		
		
		<script src="javascript/expandingWindows.js"></script>
		<script type="text/javascript" src="javascript/search.js" language="javascript"></script>
		<script type="text/javascript" src="javascript/groupSearch.js" language="javascript"></script>
		<script type="text/javascript" src="javascript/searchToAdd.js" language="javascript"> </script>
		<script type="text/javascript" language="javascript"> 
			function toggleDiv(divid){ //Function for toggling a chat window up and down
				if(document.getElementById(divid).style.display == 'none'){
					document.getElementById(divid).style.display = 'block';
				}
				else{
					document.getElementById(divid).style.display = 'none';
				}
		    }
			function expandTag(divid){ //Function for showing the full text of a tag
				if(document.getElementById(divid).style.width != 'auto'){
					document.getElementById(divid).style.width = 'auto';
				}
				else{
					document.getElementById(divid).style.width = '100px';
				}
		    }
		</script>			
	</head>

	<body>

		<!-- Banner PHP code, dynamic per page you're on -->
		<?php include 'php/banner.php';?>
		<!-- Hidden box for settings that is toggled from the banner. -->
		<div class='settingsBox' id='profileSettings' style='display:none'>
            <?php include 'profileSettings.php';?>
       	</div>


		<!--Sits around all three columns, keeping them aligned together easily. Move this around (in CSS) if you want to shift or affect all 3 columns.  -->
		<div id="columnWrapper"> 

			<!--Group links and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group-->
				<div class="maximizeAddWrapper" id="createGroupMini" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');"></div>
				<div class="sidebarAddWrapper" id="createGroupWrapper"  style="display:none">
					<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/functions.php?createGroup=true">  
						<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
						<!-- <input type="submit" name="createGroup" value="Create Group" class="hvr-fade-green button"> -->
					</form>
					<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');" >-</div>
				</div>

				<!--generates the links to groups the person is a part of-->
				<?php groupSidebar($connection, $user, $otherUser); ?>
			</div>

			<!-- Column for profile information -->
			<div id="centerColumn">

				<h1>
					<?php 
						//display profile's name
						echo "<div class='profileName'>".$profileInfo["f_name"]." ".$profileInfo["l_name"]."</div>";
					?>
				</h1>


				<?php
					echo "<div id='pictureWrapper'>";
						//If this is our profile
						if(is_null($otherUser)){
							echo "<!-- Uploading profile picture -->
							<form id='pictureUpload' action='uploadPicture.php' enctype='multipart/form-data' method='post' style='display:none' >
									<input id='pictureInputField' name='uploadedimage' type='file'>
									<input name='Upload Now' id='uploadPictureButton' class= 'button hvr-fade-green' type='submit' value='Upload Image'>
							</form>";
							echo "<a class='button hvr-fade-green' id= 'addPicture' onmousedown=\"toggleDiv('pictureUpload');\" onmousedown=\"toggleDiv('addPicture');\">+</a>";
						}
						if (($profileInfo['picture']) != 'NULL' && is_null($otherUser)){
							echo "<a class='button hvr-fade-red removePicture' href='php/functions.php?removeProfilePicture=true'>REMOVE PICTURE</a>";
						}
						//display profile picture
						//If the string is "NULL" (aka, no picture in the database for this person) then upload a silhouette instead.
						if (($profileInfo['picture']) == 'NULL'){
							echo "<img class = 'image' src='images/silhouette.jpg'>";
						}
						else{
							echo "<img class = 'image' src='uploads/profile_images/" .$profileInfo["picture"]. "'>";
						}
					echo "</div>";
				?>

				</br>
				
				<!-- profile details include tags and if this isn't the user's profile, buttons for interacting with the other user-->
				<?php 
					//if this is the user's profile page, $otherUser will be null
					if(!is_null($otherUser)){
						//check if this profile is the user's contact
						$contact = True;
						$sql = "SELECT *
								FROM contacts
								WHERE (uID = '$user' AND contact = '$otherUser')";
						$result = $connection->query($sql);
						if($result->num_rows == 0){
							$contact = False;
						}

						//check if this user is blocked by the profile
						$blockedUser = True;
						$sql = "SELECT *
								FROM u_blocks
								WHERE (uID = '$otherUser' AND blocked = '$user')";
						$result = $connection->query($sql);
						if($result->num_rows == 0){
							$blockedUser = False;
						}

						//check if this profile is blocked by the user
						$blockedProfile = True;
						$sql = "SELECT *
								FROM u_blocks
								WHERE (uID = '$user' AND blocked = '$otherUser')";
						$result = $connection->query($sql);
						if($result->num_rows == 0){
							$blockedProfile = False;
						}

						echo "<div id='profileButtonsWrapper'>";
							if(!$blockedUser){
								echo "<a href='php/functions.php?createConversation=true&uID=$otherUser' class='button profileButton hvr-fade-blue'>New Conversation</a>";
							}
							if(!$contact){
								echo "<a href='php/functions.php?uID=$otherUser&contact=$contact' class='button profileButton hvr-fade-green'>Add Contact</a>";
							}
							else{
								echo "<a href='php/functions.php?uID=$otherUser&contact=$contact' class='button profileButton hvr-fade-green'>Remove Contact</a>";
							}
						echo "</div>";
					}

					//write out the profile's tags
					include "userTags.php";
				?>
			</div>

			<!--Conversation links and notifications -->
			<div class="sidebar" id="convSidebar">
				<!--form to create a conversation-->
				<a href="php/functions.php?createConversation=true" class="maximizeAddWrapper"></a>
				<?php conversationSidebar($connection, $user, $otherUser); ?>
			</div>

	    </div>
	</body>
</HTML>