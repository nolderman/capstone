<?php 
	require_once 'php/connect.php';
	require_once 'php/sessionStatus.php';
	require_once 'php/getProfileInfo.php';



function contactList($connection, $user, $profile){

	$sql = "SELECT uID as cID, f_name as c_f_name, l_name as c_l_name
				 FROM user
				 where uID IN 
				  			-- contactID is the contact (cID) in contacts where the user=$user
				 			(SELECT contact FROM user NATURAL JOIN contacts WHERE uID ='$user')";

		if($result = $connection->query($sql)){
			//if there are no results
			if($result->num_rows == 0){
				echo "There are no contacts to display!";
			}
			else{
				//write out each group name to the sidebar and make them links
				while($contactList = $result->fetch_assoc()){
					echo "<a href = 'profile.php?uID=".$contactList['cID']."'>";
					echo "<div class='sidebarLink contactLink hvr-fade-blue'>".$contactList['c_f_name']." ".$contactList['c_l_name']."</div>";
					echo "</a></br>";
				}
			}
		}
}
?>


