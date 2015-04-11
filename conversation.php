<?php 
	require_once 'php/sessionStatus.php';
	require_once 'php/connect.php';
	include 'php/getConvoInfo.php';
?>
<!DOCTYPE html> 
<HTML5>
	<head>
		<title>Conversation Page</title>
		<link rel="stylesheet" type="text/css" href="css/conversation.css">
		<link href="css/buttons.css" rel="stylesheet" media="all">
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/conversation.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->

		<script src="javascript/jquery-1.11.2.min.js"></script>
	</head>

	<body>
		
		<?php include 'php/banner.php';?>

		<div id="columnWrapper">

			<!-- Left column -->
			<div class="sidebar" id="groupSideBar">
				<?php include 'php/participantsSidebar.php';?>
			</div>

			<!-- center column -->
			<div id="centerColumn">

				<!-- input area for your message -->
				<div id="messageInputWrapper">
					<?php 
						
						echo "<form name='sendMessage' method='POST' action='php/sendMessage.php?cID=$cID'>";
						echo "<textarea cols='50' rows='4' name='message' id='messageInput' placeholder='Type Your Message Here'></textarea>";     
						echo "<input type='submit' name='sendMessage' value='Send Message' class='button hvr-fade-green' id='sendButton'>";				
						echo "</form>";
						
						include 'php/displayMessages.php';
					?>
				</div>
			</div>

			<!--Left column. Generates the links to conversations the person is a part of-->
			<div class= "sidebar" id="convSidebar">
				<?php include 'php/convSidebar.php';?>
			</div>
		</div>

	</body>
</HTML>