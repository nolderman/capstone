<?php
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
?>
<!DOCTYPE html>
<HTML5>
	<head>
		<title>Profile Page</title>
		<link rel="stylesheet" type="text/css" href="css/profile.css">
		<link href="css/hover.css" rel="stylesheet" media="all">
		<link href="css/chatWindows.css" rel="stylesheet" type="text/css">
		<link href="css/sidebars.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
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
		$(document).ready(function() {
			$('input.typeahead').typeahead({
				name: 'typeahead',
				remote: 'php/search.php?searchInput=%QUERY'
			});
		})
		</script>
		
	</head>


	<body>

		<div class = "banner"> 
			<form name="searchBar" class="searchBar" method= "POST" action="php/search.php">  
				<input type="text" name="typeahead" class="typeahead" placeholder="Search"/>							
				<input type="submit" name="addContact" value="Add Contact" class="button"> 
			</form>		
		<a class = "content logout hvr-fade-green" href="php/userLogout.php">Logout</a>				
		</div>


		<!--Sits around all three columns, keeping them aligned together easily. Move this around (in CSS) if you want to shift or affect all 3 columns.  -->
		<div id="columnWrapper"> 

			<!--Group links and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group-->
				<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/createGroup.php">  
					<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
					<input type="submit" name="createGroup" value="Create Group" class="button">
				</form>

				<!--generates the links to groups the person is a part of-->
				<?php include 'php/groupSidebar.php';?>
			</div>


			<!-- Column for profile information -->
			<div id="centerColumn">
				<h1>
					<?php 
						$userName = $_SESSION['f_name'];
						echo $userName; //get the value of the users email from browser else
					?>
				</h1>
				<img  class = "image" src="images/silhouette.jpg">
				
				</br>
				
				<div id="userTags">
					<!--form for user to tag themself-->
					<form name="tagUser" class="tagUser"  id="tagUser" method= "POST" action="php/tagUser.php">  
						<input type="text" name = "tagName" id="tagName" class="input tagName" placeholder="Tag Name"/>	
						<input type="submit" name="addTag" value="Add Tag" class="button">
					</form>
					<?php include 'php/getUserTags.php';?>
				</div>
				
			
			</div>


			<!--Conversation links and notifications -->
			<div class="sidebar" id="convSidebar">
	<!-- 			<div class='sidebarHeader'>Conversations</div>
				<div class='sidebarContent'>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink'> TESTCONVLINK2 </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
					<div class='convLink hvr-fade-green'> TESTCONVLINK </div>
				</div> -->
				<?php include 'php/convSidebar.php';?>
			</div>
	
	    </div>


	    <!-- Wrapper div for the chat boxes at the bottom of the page. -->
		<div class="chatWindowWrapper"> 
  			<div class="hvr-bubble-top" id="smallChatWindow"  href="javascript:;" onmousedown="toggleDiv('bigChatWindow'); toggleDiv('smallChatWindow');" >Click to Expand</div>
  			<div id="bigChatWindow" style="display:none">
  				<div class="chatWindowHeader" id="chatWindowHeader" href="javascript:;" onmousedown="toggleDiv('bigChatWindow'); toggleDiv('smallChatWindow');">
  					minimize
  				</div>
				<div id="inputField">
					<form name="postMessage" method="POST" action="php/postMessageToConversation.php">
					<textarea  name="message" id="message" placeholder="Type your message here!"></textarea>
					<!-- <input type="submit" name="postMessage" value="Post Message" class="button hvr-fade-green">	 -->			
					</form>
				</div>
  			</div>
		</div>
		
	</body>
</HTML>