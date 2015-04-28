<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/functions.php';
	require_once 'php/getGroupInfo.php';
	
?>
<!DOCTYPE html>
<HTML5>
	<head>
		<link href="css/group.css" rel="stylesheet" media="all">
		<link href="css/buttons.css" rel="stylesheet" media="all">
		<script src="javascript/jquery-1.11.2.min.js"></script>
		<link href="css/banner.css" rel="stylesheet" type="text/css"> <!-- CSS file for header for main pages -->
		<link href="css/searchBar.css" rel="stylesheet" type="text/css"> <!-- CSS file for banner for main pages -->
		<link href="css/groupSettings.css" rel="stylesheet" type="text/css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="javascript/bootstrap.js"></script> 
		<script type="text/javascript" src="javascript/typeahead.js"></script>  
		<script type="text/javascript" src="javascript/search.js" language="javascript"> </script>
	</head>
	
	<body>
	
	<div class='groupSettings'>
	
	<?php 
		echo "<a href='group.php?gID=$gID' class='button'> Back To Group </a>";
		
		
			$public="";//this goes into the radio buttons and checks the one from the database
			$private="";
			if($visible){
				$public = "checked";
			}else{
				$private = "checked";
			}
	echo	"<h5> Visibility</h5>
			<form enctype='multipart/form-data' action='php/functions.php?gID=$gID&saveGroupSettings=true' method='POST'>
				<input type='radio' name='visibility' value='0' $private>Private
				<br>
				<input type='radio' name='visibility' value='1' $public>Public
				<br>
				<h5>Group Icon</h5>
				<input type='file' name='fileToUpload' id='fileToUpload'> 
				<br>
				<input type='submit' class='button' value='Save Settings'>	
			</form>
			
			<h5>Block User</h5>";
			
			echo
			"<form name='searchBar' class='content' id='searchbar' method='POST' action='php/functions.php?blockUserFromGroup=true&gID=$gID'>
				<input type='text' name='typeahead' class='typeahead' id='searchbarInput' placeholder='Search'/>	
				<input type='hidden' name='hiddenUID' id='userID' />						
				<input type='submit' name='addContact' value='Go!' class='hvr-fade-green button' id='searchButton' hideFocus='true'> 
			</form>";
			
			
				$sql = "SELECT * FROM g_blocks	NATURAL JOIN user WHERE gID=$gID";
				$result = $connection->query($sql);
		
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
						echo $row['f_name']." ".$row['l_name'];
						$uID = $row['uID'];
						echo "<a href='php/functions.php?unblockUserFromGroup=true&gID=$gID&uID=$uID'> Unblock </a>";
				}
	
			?>
				
	</div>
	
	</body>
</html>