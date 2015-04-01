
var controller;

$(function() {
	controller = new ScrollMagic.Controller();
	
	//tweens//							div, 		,time, {css params},	staggerTime 			
	var missionFadeIn = TweenMax.staggerFrom(".missionContent", 2.5, {opacity:0});
	var featuresFadeIn = TweenMax.staggerFrom(".featuresPanelContent", .5, {opacity:0}, 0.15);
	var aboutUsFadeIn = TweenMax.staggerFrom(".teamMember", .5, {opacity:0}, 0.25);
	
	// build scene
	var scene = new ScrollMagic.Scene({triggerElement: "a#top", duration: 200}) //				.addIndicators() // add indicators (requires plugin)
					.addTo(controller);
					
	//mission, features, about us scenes 
	var missionScene = new ScrollMagic.Scene({
		triggerElement:".missionContent"
		})
	.setTween(missionFadeIn)
	.addTo(controller)
	.addIndicators();
	
	var featureScene = new ScrollMagic.Scene({triggerElement:".featuresContent"})
	.setTween(featuresFadeIn)
	.addTo(controller);
	
	var aboutUsScene = new ScrollMagic.Scene({triggerElement:".aboutus"})
	.setTween(aboutUsFadeIn)
	.addTo(controller);
	
	// change behaviour of controller to animate scroll instead of jump
	controller.scrollTo(function (newpos) {
		TweenMax.to(window, 0.5, {scrollTo: {y: newpos}});
	});

	//  bind scroll to anchor links
	$(document).on("click", "a[href^=#]", function (e) {
		var id = $(this).attr("href");
		if ($(id).length > 0) {
			e.preventDefault();

			// trigger scroll
			controller.scrollTo(id);

				// if supported by the browser we can even update the URL.
			if (window.history && window.history.pushState) {
				history.pushState("", document.title, id);
			}
		}
	});
});