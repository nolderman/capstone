<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/getProfileInfo.php';
?>
<!DOCTYPE html>
<HTML5>
	<head>
		<title>Profile Page</title>
		<link href="css/profile.css" rel="stylesheet" media="all">
		<link href="css/buttons.css" rel="stylesheet" media="all">
		<link href="css/chatWindows.css" rel="stylesheet" type="text/css">
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for general styling of the right left and middle columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/tags.css" rel="stylesheet" type="text/css"> <!-- CSS file for tags -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  	
		<script src="javascript/expandingWindows.js"></script>
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

		<!-- Banner PHP code, dynamic per page you're on -->
		<?php include 'php/banner.php';?>


		<!--Sits around all three columns, keeping them aligned together easily. Move this around (in CSS) if you want to shift or affect all 3 columns.  -->
		<div id="columnWrapper"> 

			<!--Group links and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group - NOTE: THIS ONLY EXISTS FOR TESTING-->
				<div class="maximizeAddWrapper" id="createGroupMini" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');"></div>
				<div class="sidebarAddWrapper" id="createGroupWrapper"  style="display:none">
					<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/functions.php?createGroup=true">  
						<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
						<input type="submit" name="createGroup" value="Create Group" class="hvr-fade-green button">
					</form>
					<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');" >-</div>
				</div>

				<!--generates the links to groups the person is a part of-->
				<?php 
					include 'php/groupSidebar.php';
				?>
			</div>

			<!-- Column for profile information -->
			<div id="centerColumn">
				<h1>
					<?php 
						//display profile's name
						echo $profileInfo["f_name"]." ".$profileInfo["l_name"];
					?>
				</h1>

				<!--change this to profile image when that is implemented-->
				<img class = "image" src="images/silhouette.jpg">
				
				</br>
				
				<!-- profile details include tags and if this isn't the user's profile, buttons for interacting with the other user-->
				<?php include "php/profileDetails.php";?>
				
			</div>

			<!--Conversation links and notifications -->
			<div class="sidebar" id="convSidebar">
				<?php include 'php/convSidebar.php';?>
			</div>

	    </div>


<!-- 	    <!-- Wrapper div for the chat boxes at the bottom of the page. Temporarily taken out so it doesnt overlap things during presentation.
		<div class="chatWindowWrapper"> 
  			<div class="hvr-bubble-top" id="smallChatWindow"  href="javascript:;" onmousedown="toggleDiv('bigChatWindow'); toggleDiv('smallChatWindow');" >Click to Expand</div>
  			<div id="bigChatWindow" style="display:none">
  				<div class="chatWindowHeader" id="chatWindowHeader" href="javascript:;" onmousedown="toggleDiv('bigChatWindow'); toggleDiv('smallChatWindow');">
  					minimize
  				</div>
				<div id="inputField">
					<form name="postMessage" method="POST" action="php/postMessage.php">
					<textarea  name="message" id="message" placeholder="Type your message here!"></textarea>
					<!-- <input type="submit" name="postMessage" value="Post Message" class="button hvr-fade-green">			
					</form>
				</div>
  			</div>
		</div> -->
		
	</body>
</HTML>