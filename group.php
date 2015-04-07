<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
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
		<link href="css/message.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class = "banner">
			<p>BANNER: Test Text</p>
			<h1>
				<?php
					echo $groupInfo["g_name"]; 
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

				<div id="postWrapper">
				<?php 
					echo "<form name='postMessage' method='POST' action='php/postMessage.php?gID=$gID'>";
					echo "<textarea cols='50' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>";     
					echo "<input type='submit' name='postMessage' value='Post Message' class='button' id='postButton'>";				
					echo "</form>";
				?>
				</div>

				<!--Posted Messages-->
				<div class="postContent">
					<?php		
						$sql = "SELECT f_name, date_time, content FROM post NATURAL JOIN user WHERE gID = '$gID' ORDER BY date_time"; 
						$result = $connection->query($sql);//get all of the messages
										
						//print out the messages in an unordered list on the page
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							echo"<div class='message'>";
							
							echo $row["content"];
							
								echo "<div class='subMessage'>";
									echo $row["f_name"]." ".$row["date_time"];
								echo "</div>";
							
							
							
							echo  "</div>";
						}
									
					?>
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