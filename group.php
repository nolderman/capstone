<?php 
	require_once 'php/sessionStatus.php';
	require_once 'php/connect.php';
	require_once 'php/getGroupInfo.php';
?>
<!DOCTYPE html>
<HTML5>
	<head>
		<title>Group Name</title>
		<link rel="stylesheet" type="text/css" href="css/group.css">
		<script src="javascript/jquery-1.11.2.min.js"></script>
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for header for main pages -->
	</head>

	<body>
		<div class = "banner">
			<p>BANNER: Test Text</p>
			<h1>
				<?php
					echo $groupInfo['g_name']; 
				?>
			</h1>
		</div>

		<!-- wrapper for all divs within the main body of the page. -->
		<div id="columnWrapper">

			<!-- Column wrapper for group information and notifications -->
			<div class="sidebar" id="groupSidebar">
				
				<!--form to create a group-->
				<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/createGroup.php">  
					<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
					<input type="submit" name="createGroup" value="Create Group" class="button">
				</form>

				<!--generates the links to groups the person is a part of-->
				<?php include 'php/membersSidebar.php';?>

			</div>

			<!--Group's Posts, center column-->
			<div id="centerColumn">
				<!--Form to post a message-->
				<div id="postMessage">
					<form name="postMessage" method="POST" action="php/postMessage.php">
					<textarea cols="50" rows="4" name="message" id="message" placeholder="Type Your Message Here"></textarea>
					<input type="submit" name="postMessage" value="Post Message" class="button">				
					</form>
				</div>

				<!--Posted Messages-->
				<div class="postContent">
					<?php		
						$sql = "SELECT content FROM post WHERE gID = '$gID'"; 
						$result = $connection->query($sql);//get all of the messages
										
						//print out the messages in an unordered list on the page
						echo "<ul>";
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							echo  "<li>".$row['content']."</li>";
						}
						echo "</ul>";			
					?>
				</div>
			</div>

			<!--Right column. List of conversations the group contains-->
			<div class="sidebar" id="groupConversationWrapper">
				<div id="conversationFeed">
					This is where the default and other conversations' messages will appear
				</div>
				<div id="conversationInputField">
					This is where you will type and send your message
				</div>
			</div>

		</div>

	</body>
</HTML>