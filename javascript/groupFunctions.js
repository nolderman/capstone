			//click to reply function
			$(function(){
				$('.reply-post').on('click', function(e){
					e.preventDefault();
					$(this).next('.reply-form').show();
				});
			});
			
			//click to edit post
			$(function(){
				$('.edit-post').on('click', function(e){
					e.preventDefault();
					$(this).next('.edit-form').show();
				});
			});
			
			//click to edit group name
			$(function(){
				$('.editName').on('click', function(e){
					e.preventDefault();
					$(this).next('.editName-form').show();
				});
			});
			
			//click to edit reply
			$(function(){
				$('.edit-reply').on('click', function(e){
					e.preventDefault();
					$(this).next('.edit-reply-form').show();
				});
			});