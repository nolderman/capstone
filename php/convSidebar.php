<?php
	require_once 'connect.php';
	session_start(); //fetch the session variables from the database
	
	$user = $_SESSION["uID"];
	$numConversations = 10;


	echo "<div class='sidebarHeader' align='center'>Conversations</div>";
	
		$sql = "SELECT (cID, c_name) FROM (user NATURAL JOIN participates NATURAL JOIN conversation) WHERE (uID = '$user')"; //put the contact in the database
		
		if($result = $conn->query($sql)){
			//write out each conversation name to the sidebar
			echo "<div class='sidebarContent' align='center'>";
			while($convos = $result->fetch_array(MYSQLI_ASSOC)){
				echo "<a href = 'group.html?gID=".$convos['cid']."'>";
				echo "<div class='convLink'>".$convos['c_name']."</div>";
				echo "</a></br>";		
			}
		}
		else{
			echo "You have no conversations!";
		}

	echo "</div>";
?>