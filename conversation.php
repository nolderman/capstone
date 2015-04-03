<!DOCTYPE html> 
<HTML5>
<?php
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
?>
	<head>
		<title>Conversation Page</title>
		<link rel="stylesheet" type="text/css" href="css/conversation.css">
		<link href="css/hover.css" rel="stylesheet" media="all">
		<link href="css/sidebars.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<script src="javascript/jquery-1.11.2.min.js"></script>
	</head>

	<body>
		
		<div class = "banner"> 
    		<div class="logout">
				<a href="php/userLogout.php">Logout</a>
			</div>	
		</div>

		<div id="columnWrapper">

			<!--Generates the links to conversations the person is a part of-->
			<div id="convSidebar">
				<?php include 'php/convSidebar.php';?>
			</div>

			<!-- center column -->
			<div id="messageWrapper">
				<div id="messageFeed">
					This is where the conversation's messages from all members will appear in order.
				</div>

				<!-- input area for your message -->
				<div id="textInputWrapper"> 
						<form name="postMessage" method="POST" action="php/postMessageToConversation.php">
						<textarea  name="message" id="messageInput" placeholder="Type your message here!"></textarea>
						<input type="submit" name="postMessage" value="Post Message" class="button hvr-fade-green">				
						</form>
				</div>
			</div>
		</div>

	</body>
</HTML>