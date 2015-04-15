<?php 
	require_once 'php/sessionStatus.php';
	require_once 'php/connect.php';
	require_once 'php/getConvoInfo.php';
	require_once 'php/functions.php';
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
		<?php 
		include 'php/banner.php';
		echo "<form name=toGroup' method='POST' action='php/functions.php?toGroup=true&cID=$cID'>
				<input type='text' name='toGroup' placeholder='New Group Name'/>	
				<input type='submit' value='Turn Into Group' class='hvr-fade-green button'>
			</form>";
		?>

		<div id="columnWrapper">

			<!-- Left column -->
			<div class="sidebar" id="groupSideBar">
				<?php include 'php/participantsSidebar.php';?>
			</div>

			<!-- center column -->
			<div id="centerColumn">
				<div id="messageFeed">
					<?php include 'php/displayMessages.php'; ?>
				</div>
				<!-- input area for your message -->
				<div id="messageInputWrapper">
					<?php 
						
						echo "<form name='sendMessage' method='POST' action='php/functions.php?sendMessage=true&cID=$cID'>";
						echo "<textarea cols='50' rows='4' name='message' id='messageInput' placeholder='Type Your Message Here'></textarea>";     
						echo "<input type='submit' name='sendMessage' value='Send Message' class='button hvr-fade-green' id='sendButton'>";				
						echo "</form>";
						
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