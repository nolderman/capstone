<?php

echo	"<div class = 'banner'> 
			<form name='searchBar' class='content searchBar' method= 'POST' action='profile.php?uID=1'>  
				<input type='text' name='typeahead' class='typeahead' id='searchInput' placeholder='Search'/>							
				<input type='submit' name='addContact' value='Go!' class='hvr-fade-green button' id='searchButton'> 
			</form>		
		<a class = 'hvr-fade-green button content logout' href='php/userLogout.php'>Logout</a>				

			<img id='connaktSymbol' src='images/banner/center_banner.png'></img>

		</div>";

?>