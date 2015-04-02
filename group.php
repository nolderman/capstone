<!DOCTYPE html>
<HTML5>
	<head>
		<title>Group Name</title>
		<link rel="stylesheet" type="text/css" href="css/group.css">
		<script src="javascript/jquery-1.11.2.min.js"></script>
		<link href="css/sidebars.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for header for main pages -->
	</head>
	<body>
		<div class = "banner">
			<p>Some text to show that the banner exists</p>
			<h1>
				<?php
					echo $_SESSION['g_name']; 
				?>
			</h1>
		</div>

		<div id="columnWrapper"><!-- wrapper for all divs within the main body of the page. -->

			<!-- Column wrapper for group information and notifications -->
			<div id="groupSidebar">
				
				<!--form to create a group-->
				<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/createGroup.php">  
					<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
					<input type="submit" name="createGroup" value="Create Group" class="button">
				</form>

				<!--generates the links to groups the person is a part of-->
				<?php include 'php/groupSidebar.php';?>

			</div>


			<div id="postWrapper"><!--wrapper for post input and post feed-->
				<div id="postMessage">
					<form name="postMessage" method="POST" action="php/postMessage.php">
					<textarea cols="50" rows="4" name="message" id="message" placeholder="Type Your Message Here"></textarea>
					<input type="submit" name="postMessage" value="Post Message" class="button">				
					</form>
				</div>
				<div class="postFeed">
				
					<?php
					//Display all of the messages for the group
						require_once 'php/connect.php'; //connect to the database first
						
						$gID = $_SESSION['gID'];//get the group we are currently in
								
						$sql = "SELECT content FROM post WHERE gID= '$gID' "; 
								
						$result = $conn->query($sql);//get all of the messages
										
						/* associative array */
										
						echo "<ul>";
						while($row = $result->fetch_array(MYSQLI_ASSOC))  
							{
								echo  "<li>".$row['content']."</li>"; //print out the messages in an unordered list on the page
							}
						echo "</ul>";			
					?>
				</div>
			</div>

			<div id="groupConversationWrapper">
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