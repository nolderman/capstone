<!DOCTYPE html>
<HTML5>
	<?php session_start(); ?>
	<head>
		<title>~Capstone~</title>
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/hover.css">
		<link rel="stylesheet" type="text/css" href="css/banner.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
		<script src='http://codepen.io/assets/libs/fullpage/jquery.js'></script>
		<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/392/TweenMax.min.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.1/ScrollMagic.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.1/plugins/animation.gsap.js'></script>
		<script src="javascript/_dependent/greensock/plugins/ScrollToPlugin.min.js"></script>
		<script src="javascript/debug.addIndicators.js"></script>
		<script src = "javascript/index.js"></script>
	</head>
	<body>

		<div id = "wrapper">
			<div class = "banner">
				<a id="top"></a>
					<h1>Capstone</h1>

					<!--Login Form-->
					<form name="login" class="loginform cf" accept-charset="utf-8" method= "POST" action="php/userLogin.php">  			       
						 <input type="email" id="email" name="usermail" placeholder="example@email.com" required>
						 <input type="password"  id="password" name="password" placeholder="password" required>
						 <input type="submit" class="submit hvr-fade" name = "submit"  value="Login">
					</form>   

				<a href="register.html" class="signup hvr-fade"> Sign Up!</a>
			</div>	
		</div>
		
		<!--Section links-->
		<form class="move">
			<a class="hvr-fade" href="#top">Top</a>
			<a class="hvr-fade" href="#Mission">Mission</a>
			<a class="hvr-fade" href="#Features">Features</a>
			<a class="hvr-fade" href="#AboutUs">About Us</a>
		</form>
				
		<div class="panel mission">
			<a id="Mission"></a>
			<h1>Mission</h1>
			<h2 class="missionContent">
				Simplify the way students form course groups. 
				For our capstone project, we have decided to work
				on a new social-media platform. A user of this platform 
				will not have a profile, just their name and a picture. 
				The focus would be on participating in groups that people are 
				free to create and invite others to. These groups would allow
				for creating events, having discussions, posting photos, and 
				more! In addition to this, there would be a messaging component 
				so people that are not in groups with each other could still 
				communicate through this platform. 
			</h2>
		</div>
				
		<div class="panel features">
			<a id="Features"></a>
			<h1>Features</h1>
			<ol class="featuresContent">
				<h2>
				<li class="featuresPanelContent">Create a group with everyone in the room</li>
				<li class="featuresPanelContent">Create a calendar that the group can see</li>
				<li class="featuresPanelContent">Create a group meeting time from individual's schedules</li>
				<li class="featuresPanelContent">Set a  group self-destruct date</li>
				<li class="featuresPanelContent">Convert a conversation into a group</li>
				<li class="featuresPanelContent">Define group level of visibility (only share what you want)</li>
				<li class="featuresPanelContent">Create a conversation with multiple people</li>
				<li class="featuresPanelContent">Search people who have an account</li>
				<li class="featuresPanelContent">Post message and pictures to groups/conversations</li>
				<li class="featuresPanelContent">Delete post or edit your post</li>
				</h2>
			</ol>
		</div>
				
		<div class="panel aboutus">
			<a id="AboutUs"></a>
			<h1>About Us</h1>
			<div class="teamMember">
				 <img class="teamMemberImage" src="images/ericHoney.png" alt=""> 
				 <h2>Eric Sculac</h2>
			</div>
			<div class="teamMember">
				 <img class="teamMemberImage" src="images/nateTents.jpg" alt=""> 
				 <h2>Nate Olderman</h2>
			</div>
			<div class="teamMember">
				 <img class="teamMemberImage" src="images/BrandonRoberts.jpg" alt=""> 
				 <h2>Brandon Roberts</h2>
			</div>
		</div>
		
		<div class="footer">	
		</div>
		
	</body>
</HTML>