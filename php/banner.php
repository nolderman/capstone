<?php

// Parse and find the basename of the page we are on. Profile page -> profile.php -> profile, for example.
$basename = substr(strtolower(basename($_SERVER['PHP_SELF'])), 0, strlen(basename($_SERVER['PHP_SELF'])) - 4);


if ($basename != 'index') { //if you are not on the index page, aka on profile, group, or conversation pages..
    echo "<div class = 'banner'> ";

    	//No matter where you're at, Connakt is there for you.
    	echo "<div id='bannerTitle'>Connackt</div>";
		
	    // If you are on the group page
	    if ($basename == 'group') { //if we are on the group page searching on the group page we want to add this user to the group
	        $gID = $_GET["gID"];

			if($moderator){ //only allow changing info if a moderator
				echo "<a  class ='hvr-fade-green button content settingsButton' onmousedown='toggleDiv(\"groupSettings\");'> Settings </a>";
			}
	    } 

	    // If you are on the conversation page
	    if ($basename == 'conversation') {
	    		echo "<form name=toGroup' id='toGroup' class='content' method='POST' action='php/functions.php?toGroup=true&cID=$cID'>
					<input type='text' id='toGroupInput' name='toGroup' placeholder='New Group Name'/>	
					<input type='submit' value='Turn Into Group' id='toGroupButton' class='hvr-fade-green button'>
				</form>";
		}

		// If you are on YOUR profile page
	    if ($basename == 'profile' && is_null($otherUser)){
	        echo "<a class='hvr-fade-green button content settingsButton' onmousedown='toggleDiv(\"profileSettings\");'>Settings</a>";

	    	echo "<div class = 'hvr-fade-green button content' id='yourProfileButton' onmousedown='toggleDiv(\"contactsList\");'>Contacts</div>"; //shares id with 'yourProfileButton' because it has the exact same positioning as that button.
	    	echo "<div class = 'content' id='contactsList' style='display:none'>";
	       		include 'php/displayContacts.php';
	    	echo "</div>";
	    }

	    // You are on a different person's profile page, or on group or conversation page, add the "Your Profile" button.
	    if ($basename == 'profile' && !is_null($otherUser) || $basename != 'profile') {
	        echo "<a class = 'hvr-fade-green button content' id='yourProfileButton' href='profile.php'>Profile</a>";
	    }

	    echo "<form name='searchBar' class='content' id='searchbar' method= 'POST' action='profile.php'> 
	            <input type='text' name='typeahead' class='typeahead' id='searchbarInput' placeholder='Search'/>	
				<input type='hidden' name='hiddenUID' id='userID' value='' />						
				<input type='submit' name='addContact' value='Go!' class='hvr-fade-green button' id='searchButton' hideFocus='true'> 
			  </form>		

			  <a class = 'hvr-fade-green button content logout' href='php/functions.php?userLogout=true'>Logout</a>				

			  <img id='connaktSymbol' src='images/banner/center_banner.png'></img>";
    
    echo "</div>"; //Close banner div
} 


else { //this is if you're on the index page. 
    echo "<div class = 'banner'>";
    //No matter where you're at, Connakt is there for you.
    echo "<div id='bannerTitle'>Connackt</div>";

	echo	"<a id='top'></a>
					

			<!--Login Form-->
			<form name='login' class='content loginform cf' accept-charset='utf-8' method= 'POST' action='php/functions.php?userLogin=true'>  			       
				 <input type='email' id='email' name='usermail' placeholder='example@email.com' required>
				 <input type='password'  id='password' name='password' placeholder='password' required>
				 <input type='submit' class='content submit hvr-fade-blue' name = 'submit'  value='Login'>
			</form>   

		    <a href='register.php' class='content button signup hvr-fade-green'> Sign Up!</a>
			<img id='connaktSymbol' src='images/banner/center_banner.png'></img>
		  </div>";
}
?>