<!DOCTYPE html>
<HTML5>
<?php
	if (session_status() == PHP_SESSION_NONE) { //we don't have a session already
		session_start();
	}
?>
	<head>
		<link rel="stylesheet" type="text/css" href="css/register.css">
		<link rel="stylesheet" type="text/css" href="css/hover.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
		<title>Capstone</title>
	</head>
<body>

	<div id="wrapper">
		<form name="login" class="login" id = "login" method = "POST" action = "php/signup.php">
			
			<div class="header">
				<h1> Welcome!</h1>
				<span>Fill in your information and password, then hit Sign Up to start making connections!</span>
			</div>

			<!--Sign up fields-->
			<div class="content">
				<input class="input firstName" type="text" name = "firstName" id="firstName" placeholder="firstName"/>
				<input class="input lastName" type="text" name = "lastName" id="lastName" placeholder="lastName"/>
				<input type="text" name = "eMail" id="eMail" class="input email" placeholder="Email"/>
				<input type="password" name = "password" id="password" class="input password" placeholder="Password"/>
				<input type="password" name="confirm password" class="input confirm password" placeholder="Confirm Password"/>
			</div>

			<!--Submit and cancel buttons-->
			<div class="footer">
				<input type="submit" class="button hvr-fade" name="submit" value="Sign Up!"> 
				<a class="cancel"  style="text-decoration: none" href="index.">Cancel</a>
		    </div>
		</form>
	</div>
</body>
</html>