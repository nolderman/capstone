<?php
$basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);

if ($basename != 'index') { //if you are not on the index page, aka on profile, group, or conversation pages..
echo	"<div class = 'banner'> 
			<form name='searchBar' class='content searchBar' method= 'POST' action='profile.php?uID=1'>  
				<input type='text' name='typeahead' class='typeahead' id='searchInput' placeholder='Search'/>							
				<input type='submit' name='addContact' value='Go!' class='hvr-fade-green button' id='searchButton'> 
			</form>		
		<a class = 'hvr-fade-green button content logout' href='php/userLogout.php'>Logout</a>				

			<img id='connaktSymbol' src='images/banner/center_banner.png'></img>

		</div>";
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