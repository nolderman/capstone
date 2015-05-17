<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/functions.php';
	require_once 'php/sidebars.php';
	
	//the following code sets up these variables for the page's use:
	//$user - the user's ID
	//$gID - the group's ID
	//$groupInfo - an array of associative arrays with the needed information for this group
	//$members - an array of associative arrays with the needed information for the members of this group
	//$isMember - boolean for whether or not the user is a member of the group
	//$date_joined - date the user joined the group (if they are a member)
	//$moderator - boolean for whether or not the user is a moderator
	//$g_name - name of the group

	//if no group is selected, redirect to profile page
	if(!isset($_GET["gID"])){
		header('Location: profile.php');
	}

	$user = $_SESSION["uID"];
	$gID = $_GET["gID"];

	//get the info for this group that will be needed
	$groupQuery = "SELECT gID, g_name, icon, visible
					FROM (groups)
					WHERE (gID = '$gID')";
	$result = $connection->query($groupQuery);
	$groupInfo = $result->fetch_array(MYSQLI_ASSOC);

	//get the members of the group
	$memberQuery = "SELECT uID, joined
					FROM (($groupQuery) subquery0 NATURAL JOIN members)";
	$result = $connection->query($memberQuery);
	$members = $result->fetch_array(MYSQLI_ASSOC);

	if(isset($members["uID"])){
		$isMember = true;
		$moderator = groupModCheck($connection, $user, $gID);//check if the current user is a moderator of the group
		$date_joined = $members["joined"];
	}
	else{
		$isMember = false;
		$moderator = false;
	}

	$g_name = $groupInfo["g_name"];
	$visible = $groupInfo['visible'] == 1;//get whether or not the group is visible to the public


	if(!$isMember && $visible){ //if the group is visible redirect to the permissions page
		echo "<a href ='php/functions.php?requestGroupMembership=true&uID=$user'> Request Membership </a>";
		echo "<a href ='profile.php'> Back to Profile </a>";
	}
	//if they aren't a member, and the group is set to be invisible, redirect them away from the page
	if(!$isMember && !$visible){
		header('Location: profile.php');
	}

?>
<!DOCTYPE html>
<HTML5>
	<head>
		<title>Group Name</title>
		<link href="css/group.css" rel="stylesheet" media="all">

		<link href="css/buttons.css" rel="stylesheet" media="all">
		<script src="javascript/jquery-1.11.2.min.js"></script>
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left and middle columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for header for main pages -->
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/notifications.css" rel="stylesheet" type="text/css"> <!-- CSS file for notifications -->
		<link href="css/settings.css" rel="stylesheet" type="text/css"> <!-- CSS file for settings menu -->
		<link href="css/message.css" rel="stylesheet" type="text/css">
		<link href="css/tags.css" rel="stylesheet" type="text/css"> <!-- CSS file for tags -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		
		<!-- searching javascript codes -->
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" src="javascript/search.js" language="javascript"> </script>
		<script type="text/javascript" src="javascript/searchToAdd.js" language="javascript"> </script>
		<script type="text/javascript" src="javascript/blockSearch.js" language="javascript"></script>

		<script type="text/javascript" src="javascript/groupFunctions.js" language="javascript"> </script>
		<script type="text/javascript" language="javascript">		
			//Function for toggling a chat window up and down
			function toggleDiv(divid){ 
				if(document.getElementById(divid).style.display == 'none'){
					document.getElementById(divid).style.display = 'block';
				}
				else{
					document.getElementById(divid).style.display = 'none';
				}
		    }
		    function expandTag(divid){ //Function for showing the full text of a tag
				if(document.getElementById(divid).style.width != 'auto'){
					document.getElementById(divid).style.minWidth = '110px';
					document.getElementById(divid).style.width = 'auto';
				}
				else{
					document.getElementById(divid).style.minWidth = '100px';
					document.getElementById(divid).style.width = '100px';
				}
		    }
		</script>	
	</head>

	<body>
		<?php 
			include 'php/banner.php';
			markAsRead($connection, $gID, $_SESSION['uID']);//mark all posts from this group as read by the user
		?>
			<div class='settingsBox' id='groupSettings' style='display:none'>
				<?php include 'php/groupSettings.php';?>
			</div>

		<!-- wrapper for all divs within the main body of the page. -->
		<div id="columnWrapper">

			<!-- Column wrapper for group information and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group - NOTE: THIS ONLY EXISTS FOR TESTING-->
				<div class="maximizeAddWrapper" id="createGroupMini" href:"javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');"></div>
				<div class="sidebarAddWrapper" id="createGroupWrapper"  style="display:none">
				<?php
				echo "
					<form name='searchBar' class='content' id='searchbar' method= 'POST' action='php/functions.php?addUserToGroup=true&gID=$gID'> 
						<input type='text' name='typeahead' class='typeaheadToAdd' id='searchbarInput' placeholder='Add User'/>	
						<input type='hidden' name='hiddenUID' id='userIDToAdd' value='' />						
						<input type='submit' name='addContact' value='Add User' class='hvr-fade-green button' id='searchButton' hideFocus='true'> 
					</form>	";
					?>
					
					<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');" >-</div>
				</div>

				<!--generates the links to groups the person is a part of-->
				<?php				
					membersSidebar($connection,$user,$gID,$moderator,$members);
				?>

			</div>

			<!--Group's Posts, center column-->
			<div id="centerColumn">
				<!--Form to post a message-->
				<div id="postWrapper">
				<?php 
				
					echo "<div class='groupInfoWrapper'>";
					$sql = "SELECT icon FROM groups WHERE gID='$gID'";
					$result = $connection->query($sql);
					$row = $result->fetch_array(MYSQLI_ASSOC);
					echo "<h3> $g_name </h3>";
					if(!($row['icon'] == 'NULL')){
						
						$imageLocation = $row['icon'];
						echo  "<img src='uploads/$imageLocation' class = 'image'>"; 
					}else{
						echo "<img src='images/worldBase.png' class = 'image'>"; 
					}
					
					require_once 'php/groupTags.php';

					
					if($moderator){
												}
						echo "</div>";

					//form to post a message to the group
					echo "<form name='postMessage' method='POST' action='php/functions.php?postMessage=true&gID=$gID'>
								<textarea cols='50' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>     
								<input type='submit' name='postMessage' value='Post' class='button' id='postButton'>				
						</form>";
				?>
				</div>

				<!--Posted Messages-->
				<div class="postContent">
					<?php require_once 'php/displayPosts.php';?>
				</div>
			</div>

			<!--Right column. List of conversations the group contains-->
			<div class="sidebar" id="convSidebar">
				<!--generates the links to groups the person is a part of-->
				<?php				
					groupConvoSidebar($connection,$user,$gID);
				?>
			</div>

		</div>

	</body>
</HTML>