<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/getProfileInfo.php';

//Generates a sidebar listing the groups the user is a member of
//if on a profile page that is not the user's, the sidebar will only display groups in common with the viewed profile
//Parameters:
//$connection - the connection to the database
//$user - the user's ID number
//$profile - ID number of the profile the user is viewing (if on profile page and it is not the user's)
function contactList($connection, $user, $profile){

	//get the contact's information
	$sql = "SELECT contact
			FROM (contacts NATURAL JOIN user) 
			WHERE (uID ='$user')";
	$result = $connection->query($sql);
	$contactResult = $result->fetch_assoc();
	$contactID = $contactResult['contact'];
	$sql2 = "SELECT  f_name, l_name
			FROM (user) 
			WHERE (uID ='$contactID')";



		if($result2 = $connection->query($sql2)){
			//if there are no results
			if($result2->num_rows == 0){
				echo "There are no contacts to display!";
			}
			else{
				//write out each group name to the sidebar and make them links
				while($contactList = $result2->fetch_assoc()){
					echo "<a href = 'profile.php?uID=".$contactID."'>";
					echo "<div class='sidebarLink contactLink hvr-fade-blue'>".$contactList['f_name']." ".$contactList['l_name']."</div>";
					echo "</a></br>";
				}
			}
		}
}



?>


