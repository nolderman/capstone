<?php 
	// require_once 'php/connect.php';
	// require_once 'php/sessionStatus.php';
	// require_once 'php/functions.php';


	$tags_public="";//this goes into the radio buttons and checks the one from the database
	$tags_private="";
	if($tags_visible){
		$tags_public = "checked";
	}else{
		$tags_private = "checked";
	}
	$profile_public="";//this goes into the radio buttons and checks the one from the database
	$profile_private="";
	if($profile_visible){
		$profile_public = "checked";
	}else{
		$profile_private = "checked";
	}
	echo	"<h5> Visibility</h5>
	<form enctype='multipart/form-data' action='php/functions.php?saveProfileSettings=true' method='POST'>
		<input type='radio' name='tags_visible' value='0' $tags_private>Keep tags private
		<br>
		<input type='radio' name='tags_visible' value='1' $tags_public>Keep tags public
		<br>

		<br>
		<input type='radio' name='profile_visible' value='0' $profile_private>Keep profile private
		<br>
		<input type='radio' name='profile_visible' value='1' $profile_public>Keep profile public
		<br>

		<input type='submit' class='button-hollow-green hvr-fade-green' value='Save Settings'>	
	</form>
	<br>";

	echo "<a class='button-hollow-green hvr-fade-red' href='php/functions.php?removeUserFromAll=true'> Delete Profile </a>";

	echo "<h5>Block User</h5>";

	echo
	"<form name='searchBar' class='content' id='searchbar' method='POST' action='php/functions.php?blockUser=true'>
		<input type='text' name='typeahead' class='typeahead' id='searchbarInput' placeholder='Search'/>	
		<input type='hidden' name='blockedHiddenUID' id='blockedUserID' />						
		<input type='submit' name='blockUser' value='Block User' class='hvr-fade-red button-hollow-green' id='searchButton' hideFocus='true'> 
	</form>";

	$sql = "SELECT uID as blockedID, f_name as b_f_name, l_name as b_l_name
		 FROM user
		 where uID IN 
		  			-- contactID is the contact (cID) in contacts where the user=$user
		 			(SELECT blocked FROM user NATURAL JOIN u_blocks WHERE uID ='$user')";

	// $sql = "SELECT * FROM u_blocks	NATURAL JOIN user WHERE uID=$user";
	$result = $connection->query($sql);

	while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$blockedUID = $row['blockedID'];
			$blocked_f_name = $row['b_f_name'];
			$blocked_l_name = $row['b_l_name'];
			echo $blocked_f_name." ".$blocked_l_name;
			echo "<a href='php/functions.php?unBlockUser=true?&blockedUser=$blockedUID'> Unblock </a>";
	}

?>
	
