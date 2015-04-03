<!DOCTYPE html>
<HTML5>
<?php
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
?>
	<head>
		<title>Group Name</title>
		<link rel="stylesheet" type="text/css" href="css/group.css">
		<script src="javascript/jquery-1.11.2.min.js"></script>
		<link href="css/sidebars.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for header for main pages -->
	</head>

	<body>
		<div class = "banner">
			<p>BANNER: Test Text</p>
			<h1>
				<?php
					echo $_SESSION['g_name']; 
				?>
			</h1>
		</div>

		<!-- wrapper for all divs within the main body of the page. -->
		<div id="columnWrapper">

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

			<!--Group's Posts-->
			<div id="postWrapper">
				<!--Form to post a message-->
				<div id="postMessage">
					<form name="postMessage" method="POST" action="php/postMessage.php">
					<textarea cols="50" rows="4" name="message" id="message" placeholder="Type Your Message Here"></textarea>
					<input type="submit" name="postMessage" value="Post Message" class="button">				
					</form>
				</div>

				<!--Posted Messages-->
				<div class="postFeed">
					<?php
						require_once 'php/connect.php'; //connect to the database
						
						$gID = $_SESSION['gID'];//get the group we are currently in
								
						$sql = "SELECT content FROM post WHERE gID= '$gID' "; 
						$result = $conn->query($sql);//get all of the messages
										
						//print out the messages in an unordered list on the page
						echo "<ul>";
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							echo  "<li>".$row['content']."</li>";
						}
						echo "</ul>";			
					?>
				</div>
			</div>

			<!--List of conversations the group contains-->
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