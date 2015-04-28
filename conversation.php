<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/functions.php';
	require_once 'php/sidebars.php';
	require_once 'php/getConvoInfo.php';
?>
<!DOCTYPE html> 
<HTML5>
	<head>
		<title>Conversation Page</title>
		<link rel="stylesheet" type="text/css" href="css/conversation.css">
		<link href="css/buttons.css" rel="stylesheet" media="all">
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/conversation.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" src="javascript/search.js" language="javascript"> </script>
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
		<script>
		$(document).ready(function() {//start looking for this after we have loaded everything on the page
			$('.typeahead').typeahead({ //input field of typeahead with value of f_name!
				name: 'typeahead',
				displayKey: 'f_name',
				valueKey: 'f_name',
				remote: 'php/functions.php?searchInput=%QUERY'
			})
			.on('typeahead:opened', onOpened)
			.on('typeahead:selected', onAutocompleted)
			.on('typeahead:autocompleted', onSelected);
 
			function onOpened($e) {
				console.log('opened');
			}
 
			function onAutocompleted($e, datum) {
				console.log('autocompleted');
				console.log(datum["f_name"]);
				console.log(datum["uID"]);
				document.getElementById('userID').value = datum["uID"];
			}
 
			function onSelected($e, datum) {
				console.log('selected');
				console.log(datum);
			}
		})
		</script>
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
				<!--form to add a participant-->
				<div class="maximizeAddWrapper" id="addParticipantMini" href="javascript:;" onmousedown="toggleDiv('addParticipantWrapper'); toggleDiv('addParticipantMini');"></div>
				<div class="sidebarAddWrapper" id="addParticipantWrapper"  style="display:none">
					<form name="searchBar" class="content"  id="searchbar" method= "POST" action="php/functions.php?addParticipant=true">  
						<input type="text" name = "typeahead" id="searchbarInput" class="typeahead" placeholder="User's Name"/>	
						<input type='hidden' name='hiddenUID' id='userID' value='' />
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
				<!-- <div class="maximizeAddWrapper" id="createConvMini" href="javascript:;" onmousedown="toggleDiv('createConvWrapper'); toggleDiv('createConvMini');"></div>
				<div class="sidebarAddWrapper" id="createConvWrapper"  style="display:none">
					<form name="createConversation" class="createConversation"  id="createConversation" method= "POST" action="php/functions.php?createConversation=true">  
						<input type="text" name = "conversationName" id="conversationName" class="input conversationName" placeholder="Conversation Name"/>	
						<input type="submit" name="createConversation" value="Create Conversation" class="hvr-fade-green button">
					</form>
					<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('createConvWrapper'); toggleDiv('createConvMini');" >-</div>
				</div> -->
				<?php conversationSidebar($connection, $user, null); ?>
			</div>
		</div>

	</body>
</HTML>