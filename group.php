<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/functions.php';
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
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/message.css" rel="stylesheet" type="text/css">
		<link href="css/tags.css" rel="stylesheet" type="text/css"> <!-- CSS file for tags -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" language="javascript"> 
			//click to reply function
			$(function(){
				$('.reply-post').on('click', function(e){
					e.preventDefault();
					$(this).next('.reply-form').show();
				});
			});
			
			//click to edit post
			$(function(){
				$('.edit-post').on('click', function(e){
					e.preventDefault();
					$(this).next('.edit-form').show();
				});
			});
			
			//click to edit group name
			$(function(){
				$('.editName').on('click', function(e){
					e.preventDefault();
					$(this).next('.editName-form').show();
				});
			});
		
			//Function for toggling a chat window up and down
			function toggleDiv(divid){ 
				if(document.getElementById(divid).style.display == 'none'){
					document.getElementById(divid).style.display = 'block';
				}
				else{
					document.getElementById(divid).style.display = 'none';
				}
		    }
			
			//function for searching using the php page and %QUERY which is a typeahead constant for the user input
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
			require_once 'php/groupTags.php';
			if($moderator){
				echo "<a class='button' href='php/functions.php?deleteGroup=true&gID=$gID'>Delete Group </a>";
			}
			
			
		?>

		<!-- wrapper for all divs within the main body of the page. -->
		<div id="columnWrapper">

			<!-- Column wrapper for group information and notifications -->
			<div class="sidebar" id="groupSidebar">
				<!--form to create a group - NOTE: THIS ONLY EXISTS FOR TESTING-->
				<div class="maximizeAddWrapper" id="createGroupMini" href:"javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');"></div>
				<div class="sidebarAddWrapper" id="createGroupWrapper"  style="display:none">
					<form name="createGroup" class="createGroup"  id="createGroup" method= "POST" action="php/createGroup.php">  
						<input type="text" name = "groupName" id="groupName" class="input groupName" placeholder="Group Name"/>	
						<input type="submit" name="createGroup" value="Create Group" class="hvr-fade-green button">
					</form>
					<div class="minimizeAddWrapper" href="javascript:;" onmousedown="toggleDiv('createGroupWrapper'); toggleDiv('createGroupMini');" >-</div>
				</div>

				<!--generates the links to groups the person is a part of-->
				<?php include 'php/membersSidebar.php';?>

			</div>

			<!--Group's Posts, center column-->
			<div id="centerColumn">
				<!--Form to post a message-->
				<div id="postWrapper">
				<?php 
					echo "<div class='groupName' >$g_name
							<a href='' id='$gID' class ='editName groupActionLink'> Edit Group Name </a>
							<form class='editName-form' name='editName' method='POST' action='php/functions.php?editName=true&gID=$gID' >
								<input name='editName' value='$g_name'  />
								<input class='button' type='submit' value='Change Name' />
							</form>	
						</div>";
					
					echo "<form name='postMessage' method='POST' action='php/functions.php?postMessage=true&gID=$gID'>;
								<textarea cols='50' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>;     
								<input type='submit' name='postMessage' value='Post' class='button' id='postButton'>;				
						</form>";
				?>
				</div>

				<!--Posted Messages-->
				<div class="postContent">
					<?php		
						$sql = "SELECT pID,uID, gID, f_name, date_time, content FROM post NATURAL JOIN user WHERE gID = '$gID' ORDER BY date_time"; 
						$result = $connection->query($sql);//get all of the messages
						
						//print out the messages in an unordered list on the page
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							$posterID = $row["uID"];
							$gID = $row["gID"];
							$date_time = $row["date_time"];
							$content = $row["content"];
							echo"<div class='post'>";
							
							$content = $row['content'];
							echo $content;
								echo "<div class='subPost'>";
									echo $row['f_name']." ".$row['date_time'];
									$pID= $row['pID'];
									
									//reply to the post
									echo "<a href='' class ='reply-post groupActionLink'> Reply </a>
										<form class='reply-form' method='POST' action='php/functions.php?replyToPost=true&gID=$gID&pID=$pID' >
											<textarea cols='20' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>
											<input class='button' type='submit' value='Reply' />
										</form>";
									
									//delete the post
									if($posterID == $_SESSION['uID'] || $moderator){
										echo "<a class='groupActionLink' href='php/functions.php?deletePost=true&gID=$gID&pID=$pID'>Delete Post</a>";
									}
									
									//edit the post	
									echo "<a href='' id='$pID' class ='edit-post groupActionLink'> Edit Post </a>
										<form class='edit-form' name='editPost' method='POST' action='php/functions.php?editPost=true&gID=$gID&pID=$pID' >
											<textarea cols='20' rows='4' name='editPost' id='postInput'>$content</textarea>
											<input class='button' type='submit' value='Accept' />
										</form>";
								echo "</div>";
							//display replies
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