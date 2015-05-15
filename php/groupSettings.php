<?php 
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
		<input type='submit' class='hvr-fade-green button-hollow-green' value='Save Settings'>	
	</form>
	<br>";

	if($moderator){
		echo "<a class='button-hollow-green hvr-fade-red' href='php/functions.php?deleteGroup=true&gID=$gID'>Delete Group </a>";
	}
	echo "<br><br>";
	echo	"<a id='$gID' class ='button button-hollow-green hvr-fade-green' onmousedown='toggleDiv(\"editName-form\");'>Edit Group Name</a>
			<br><br>
			<form  id='editName-form' style='display:none' name='editName' method='POST' action='php/functions.php?editName=true&gID=$gID' >
				<input name='editName' value='$g_name'/>
				<input class='button' type='submit' value='Change Name'/>
			</form>";	

	echo "<h5>Block User</h5>";

	echo	"<form name='searchBar' class='content' id='searchbar' method='POST' action='php/functions.php?blockUserFromGroup=true&gID=$gID'>
				<input type='text' name='typeahead' class='typeahead' id='searchbarInput' placeholder='Search'/>	
				<input type='hidden' name='blockedHiddenUID' id='blockedUserID' />						
				<input type='submit' name='addContact' value='Block User' class='hvr-fade-red button-hollow-green' id='searchButton' hideFocus='true'> 
			</form>";


	$sql = "SELECT * FROM g_blocks	NATURAL JOIN user WHERE gID=$gID";
	$result = $connection->query($sql);

	while($row = $result->fetch_array(MYSQLI_ASSOC)){
			echo $row['f_name']." ".$row['l_name'];
			$uID = $row['uID'];
			echo "<a href='php/functions.php?unblockUserFromGroup=true&gID=$gID&uID=$uID'> Unblock </a>";
	}
?>