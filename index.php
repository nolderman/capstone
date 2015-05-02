<?php require_once 'php/connect.php'; ?>
<!DOCTYPE html>
<HTML5>
	<head>
		<title>Connackt</title>
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/hover.css">
		<link rel="stylesheet" type="text/css" href="css/banner.css">
		<link rel="stylesheet" type="text/css" href="css/buttons.css">
	
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
			<?php include 'php/banner.php';?>
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
			Most social networking sites are focused around the individual. 
			Communication gets lost in a broadcasted wall of miscellaneous 
			posts that often contain no information you are interested in. 
			People are starting to move away from this paradigm in search 
			of direct contact and privacy. Connackt strives to make that 
			shift and focus on the connections between people. You can use 
			Connackt to message another person or start a group of people 
			who share a similar interest. Messages are simple exchanges between 
			two or more users with a straight forward interface to facilitate it. 
			If you believe you want a more structured environment, you can create 
			a group or shift over an existing conversation with all of it's participants 
			into a group. The group page allows for posting and replies but it retains 
			a conversation as part of the group for a quick exchange that doesn't
			require any kind of structure. Connackt is the straight forward answer for quick communication.
			</h2>
		</div>
				
		<div class="panel features">
			<a id="Features"></a>
			<h1>Features</h1>
			<ol class="featuresContent">
				<h2>
				<li class="featuresPanelContent">Simply create conversations or groups</li>
				<li class="featuresPanelContent">Define a level of visibility for your profile and groups (only share what you want)</li>
				<li class="featuresPanelContent">Convert a conversation into a group in one click</li>
				<li class="featuresPanelContent">Create a conversation with multiple people</li>
				<li class="featuresPanelContent">Search people who have an account set to visible</li>
				<li class="featuresPanelContent">Post message to groups/conversations</li>
				<li class="featuresPanelContent">Edit group name and change group visibility if a moderator</li>
				<li class="featuresPanelContent">Delete post or edit your post</li>
				<li class="featuresPanelContent">Block users from messaging you or adding you to a group</li>
				<li class="featuresPanelContent">Block users from joining a group</li>
				<li class="featuresPanelContent">Quickly see how many unread messages you have for all groups and conversations</li>
				<li class="featuresPanelContent">Delete post or edit your post</li>
				</h2>
			</ol>
		</div>
				
		<div class="panel aboutus">
			<a id="AboutUs"></a>
			<h1>About Us</h1>
			<div class="teamMember">
				 <img class="teamMemberImage" src="images/EricSculac.jpg" alt=""> 
				 <h2>Eric Sculac</h2>
			</div>
			<div class="teamMember">
				 <img class="teamMemberImage" src="images/NateOlderman.jpg" alt=""> 
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