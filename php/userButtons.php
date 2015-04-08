<?php
//buttons still need functionality to be added
if(!$blockedUser){
	echo "<div class='button'>Message </div>";
}

if(!$contact){
	echo "<a href='addContact.php?uID=$profile' class='button'>Add Contact</a>";
	echo "<br>";
}

if(!$blockedProfile){
	echo "<a href='blockUser.php?uID=$profile' class='button'>Block </a>";
}
?>