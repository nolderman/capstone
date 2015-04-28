<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/functions.php';
	require_once 'php/sidebars.php';
	require_once 'php/getGroupInfo.php';
?>
<!DOCTYPE html>
<HTML5>
	<head>
		<title>Group Name</title>
		<link href="css/group.css" rel="stylesheet" media="all">
		<link href="css/buttons.css" rel="stylesheet" media="all">
		<script src="javascript/jquery-1.11.2.min.js"></script>
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for header for main pages -->
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/message.css" rel="stylesheet" type="text/css">
		<link href="css/tags.css" rel="stylesheet" type="text/css"> <!-- CSS file for tags -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" src="javascript/search.js" language="javascript"> </script>
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
		</script>	
	</head>

	<body>
		<?php 
			include 'php/banner.php';
			require_once 'php/groupTags.php';
			//markAsRead($connection, $gID, $_SESSION['uID']);//mark all posts from this group as read by the user
			if($moderator){
				echo "<a class='button' href='php/functions.php?deleteGroup=true&gID=$gID'>Delete Group </a>";
			}	
		?>

		<!-- wrapper for all divs within the main body of the page. -->
		<div id="columnWrapper">

			<!-- Column wrapper for group information and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group - NOTE: THIS ONLY EXISTS FOR TESTING-->
				<div class="maximizeAddWrapper" id="createGroupMini" href:"javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');></div>
				<div class="sidebarAddWrapper" id="createGroupWrapper"  style="display:none">
					<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/createGroup.php">  
						<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
						<input type="submit" name="createGroup" value="Create Group" class="hvr-fade-green button">
					</form>
					<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');" >-</div>
				</div>

				<!--generates the links to groups the person is a part of-->
				<?php membersSidebar($user,$moderator,$members); ?>

			</div>

			<!--Group's Posts, center column-->
			<div id="centerColumn">
				<!--Form to post a message-->
				<div id="postWrapper">
				<?php 
					echo "<div class='groupName' >$g_name";
					if($moderator){
						echo	"<a href='' id='$gID' class ='editName groupActionLink'> Edit Group Name </a>
								<form class='editName-form' name='editName' method='POST' action='php/functions.php?editName=true&gID=$gID' >
									<input name='editName' value='$g_name'  />
									<input class='button' type='submit' value='Change Name' />
								</form>";	
						}
						echo "</div>";
				
					echo "<form name='postMessage' method='POST' action='php/functions.php?postMessage=true&gID=$gID'>;
								<textarea cols='50' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>;     
								<input type='submit' name='postMessage' value='Post' class='button' id='postButton'>;				
						</form>";
				?>
				</div>

				<!--Posted Messages-->
				<div class="postContent">
					<?php require_once 'php/displayPosts.php'; ?>
				</div>
			</div>

			<!--Right column. List of conversations the group contains-->
			<div class="sidebar" id="convSidebar">
				<div class="sidebarContent">
					<div id="conversationFeed">
						This is where the default and other conversations' messages will appear
					</div>
					<div id="conversationInputField">
						This is where you will type and send your message
					</div>
				</div>
			</div>

		</div>

	</body>
</HTML>