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
		<link href="css/columns.css" rel="stylesheet" type="text/css"> <!-- CSS file for right and left columns -->
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<script src="javascript/expandingWindows.js"></script>
		<script language="javascript"> 
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
		<div class = "banner"> 
			<img id="connaktSymbol" src="images/banner/center banner.png"></img>
			<a class = "content logout hvr-fade-green" href="php/userLogout.php">Logout</a>
			
			<!--OLD CODE, will be used when designing contact list
				<div class="addContact">
					<form name="addContact" class="addContact"  id="addContact" method= "POST" action="php/addContact.php">  
						<input type="text" name = "contactEmail" id="contactEmail" class="input email" placeholder="Friend Email"/>							
						<input type="submit" name="addContact" value="Add Contact" class="button"> 
					</form>
				</div>	
			-->
		</div>


		<!--Sits around all three columns, keeping them aligned together easily. Move this around (in CSS) if you want to shift or affect all 3 columns.  -->
		<div id="columnWrapper"> 

			<!--Group links and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group - NOTE: THIS ONLY EXISTS FOR TESTING-->
				<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/createGroup.php">  
					<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
					<input type="submit" name="createGroup" value="Create Group" class="button">
				</form>

				<!--generates the links to groups the person is a part of-->
				<?php 
					include 'php/groupSidebar.php';
				?>
			</div>

			<!-- Column for profile information -->
			<div id="centerColumn">
				<h1>
					<?php 
						$userName = $_SESSION['f_name']." ".$_SESSION['l_name'];
						echo $userName; //get the value of the users email from browser else
					?>
				</h1>
				<img  class = "image" src="images/silhouette.jpg">
				</br>
				<?php
					//get variable will be set if they were redirected to profile through a link, if it isn't set this is "your" profile page
					//if this is "your" profile page it will be the same as session uID
					if($_SESSION['uID'] == $_GET['uID'] || is_null($_GET['uID'])){
						echo "<div id = 'userTags'>";
							//form for user to tag themself
							echo "<form name='tagUser' class='tagUser' id='tagUser' method= 'POST' action='php/tagUser.php'>";  
								echo "<input type='text' name = 'tagName' id='tagName' class='input tagName' placeholder='Tag Name'/>";	
								echo "<input type='submit' name='addTag' value='Add Tag' class='button'>";
							echo "</form>";

						include 'php/userTags.php';
						echo "</div>";
					}
					else{
						//temporary placeholders - need to add functionality
						echo "Add Contact";
						echo "Message";
						echo "Block";
					}	
				?>
			</div>

			<!--Conversation links and notifications -->
			<div class="sidebar" id="convSidebar">
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