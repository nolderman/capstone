<?php
// Parse and find the basename of the page we are on. Profile page -> profile.php -> profile, for example.
$basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);

if ($basename != 'index') { //if you are not on the index page, aka on profile, group, or conversation pages..
echo	"<div class = 'banner'> 
			<form name='searchBar' class='content' id='searchbar' method= 'POST' action='profile.php'>  
				<input type='text' name='typeahead' class='typeahead' id='searchbarInput' placeholder='Search'/>	
				<input type='hidden' name='hiddenUID' id='userID' value='' />						
				<input type='submit' name='addContact' value='Go!' class='hvr-fade-green button' id='searchButton' hideFocus='true'> 
			</form>		

			<a class = 'hvr-fade-green button content logout' href='php/userLogout.php'>Logout</a>				

			<img id='connaktSymbol' src='images/banner/center_banner.png'></img>";

// You are on a different person's profile page, or on group or conversation page, add the "Your Profile" button.
if ($basename == 'profile' && !$ownsPage || $basename != 'profile'){
echo 	"<a class = 'hvr-fade-green button content' id='yourProfileButton' href='profile.php'>Your Profile</a>";
}			

echo	"</div>";	
}
else {
echo			"<div class = 'banner'>
				<a id='top'></a>
					

					<!--Login Form-->
					<form name='login' class='content loginform cf' accept-charset='utf-8' method= 'POST' action='php/userLogin.php'>  			       
						 <input type='email' id='email' name='usermail' placeholder='example@email.com' required>
						 <input type='password'  id='password' name='password' placeholder='password' required>
						 <input type='submit' class='submit hvr-fade-blue' name = 'submit'  value='Login'>
					</form>   

				<a href='register.php' class='content signup hvr-fade-green'> Sign Up!</a>
				<img id='connaktSymbol' src='images/banner/center_banner.png'></img>
			</div>";
}
?>