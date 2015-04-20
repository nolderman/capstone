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
					echo "<form name='postMessage' method='POST' action='php/functions.php?postMessage=true&gID=$gID'>";
					echo "<textarea cols='50' rows='4' name='message' id='postInput' placeholder='Type Your Message Here'></textarea>";     
					echo "<input type='submit' name='postMessage' value='Post Message' class='button' id='postButton'>";				
					echo "</form>";
				?>
				</div>

				<!--Posted Messages-->
				<div class="postContent">
					<?php		
						$sql = "SELECT uID, gID, f_name, date_time, content FROM post NATURAL JOIN user WHERE gID = '$gID' ORDER BY date_time"; 
						$result = $connection->query($sql);//get all of the messages
						
						//print out the messages in an unordered list on the page
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							$posterID = $row["uID"];
							$gID = $row["gID"];
							$date_time = $row["date_time"];
							$content = $row["content"];
							echo"<div class='post'>";
							
							echo $row["content"];
							
								echo "<div class='subPost'>";
									echo $row['f_name']." ".$row['date_time'];
									if($posterID == $_SESSION['uID'] || $moderator){
										echo "<a href='php/functions.php?deletePost=true&uID=$posterID&gID=$gID&date_time=$date_time'>Delete Post</a>";
									}
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