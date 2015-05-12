<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/sidebars.php';
	require_once 'php/functions.php';

	//the following code sets up these variables for the page's use:
	//$user - the user's ID
	//$cID - the conversation's ID
	//$convoName - the name of this conversation (name only exists if the convo is attached to a group)
	//$joined - date the user joined the conversation

	//if no conversation is set, redirect to profile page
	if(!isset($_GET["cID"])){
		header('Location: profile.php');
	}

	//sets the basic variables
	$user = $_SESSION["uID"];
	$cID = $_GET["cID"];


	//query database for the conversation info that will be needed
	//first, check if this user is a participant of the conversation, and direct away if they aren't
	$sql = "SELECT uID, joined
			FROM (participates)
			WHERE (uID = '$user' AND cID = '$cID')";
	$result = $connection->query($sql);
	if($result->num_rows == 0){
		header('Location: profile.php');
	}
	else{
		$participant = $result->fetch_array(MYSQLI_ASSOC);
		$joined = $participant["joined"];
	}

	//second, get the convo name
	$sql = "SELECT c_name
			FROM (conversation)
			WHERE (cID = '$cID')";
	$result = $connection->query($sql);
	$getName = $result->fetch_array(MYSQLI_ASSOC);
	$convoName = $getName["c_name"];

	//third set as this user read the messages
	$updateUnreadQuery = "UPDATE participates
	                      SET unread_count = '0'
	                      WHERE (uID = '$user' AND cID = '$cID')";
	$connection->query($updateUnreadQuery);
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
		<link href="css/notifications.css" rel="stylesheet" type="text/css"> <!-- CSS file for notifications -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		<!-- searching codes -->
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" src="javascript/search.js"> </script>
		<script type="text/javascript" src="javascript/searchToAdd.js"> </script>
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->

		<script src="javascript/jquery-1.11.2.min.js"></script>
		<script type="text/javascript" language="javascript"> 
			function toggleDiv(divid){ //Function for toggling a chat window up and down
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
		<?php include 'php/banner.php';	?>

		<div id="columnWrapper">

			<!-- Left column -->
			<div class="sidebar" id="groupSideBar">	
				<!--form to add a participant-->
				
					<div class="maximizeAddWrapper" id="addParticipantMini" href="javascript:;" onmousedown="toggleDiv('addParticipantWrapper'); toggleDiv('addParticipantMini');"></div>
					<div class="sidebarAddWrapper" id="addParticipantWrapper"  style="display:none">
						<?php
						echo "<form name='searchBar' class='content'  id='searchbar' method= 'POST' action='php/functions.php?addParticipant=true&cID=$cID'>";  
						?>
							<input type="text" name = "typeaheadToAdd" id="searchbarInput" class="typeaheadToAdd" placeholder="User's Name"/>	
							<input type='hidden' name='hiddenUID' id='userIDToAdd' value='' />
							<input type="submit" name="addParticipant" value="Add Participant" class="hvr-fade-green button">
						</form>
						<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('addParticipantWrapper'); toggleDiv('addParticipantMini');" >-</div>
					</div>

				 <?php participantSidebar($connection, $cID, $user); ?>
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
				<!--form to create a conversation-->
				<a href="php/functions.php?createConversation=true" class="maximizeAddWrapper"></a>
				<?php conversationSidebar($connection, $user, null); ?>
			</div>
		</div>

	</body>
</HTML>