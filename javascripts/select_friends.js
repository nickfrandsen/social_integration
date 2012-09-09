(function(jQuery) {

	function download_twitter_friends(){
		jQuery(".loading_gif").show();
		jQuery.get('/select-friend/twitterFriends',
				function(data){
				jQuery("#twitter_friends").append(data);
				jQuery(".loading_gif").hide();
				},
				"html"
		);
	}

	function download_facebook_friends(){
		jQuery(".loading_gif").show();
		jQuery.get('/select-friend/facebookFriends',
				function(data){
				jQuery("#facebook_friends").append(data);
				jQuery(".loading_gif").hide();
				},
				"html"
		);
	}

	jQuery(document).on("click", ".twitter_friend", function(event){
		var data = jQuery(this).data();
		jQuery("#search_for_twitter_friends").val(jQuery(this).find("h2").text());
		jQuery("#selected_twitter_id").val(data.twitterid);
		jQuery("#twitter_search_img").attr("src", jQuery(this).find("img").attr("src"));
	})

	jQuery(document).on("click", ".facebook_friend", function(event){
		var data = jQuery(this).data();
		jQuery("#search_for_facebook_friends").val(jQuery(this).find("h2").text());
		jQuery("#selected_facebook_id").val(data.facebookid);
		jQuery("#facebook_search_img").attr("src", jQuery(this).find("img").attr("src"));
	})

	jQuery("#search_for_facebook_friends").keyup(function(){
		var value = jQuery("#search_for_facebook_friends").val().toLowerCase();
		jQuery(".facebook_friend").css("display", "none");
		jQuery(".facebook_friend[data-name^='"+value+"']").show();
	});

	jQuery("#search_for_twitter_friends").keyup(function(){
		var value = jQuery("#search_for_twitter_friends").val().toLowerCase();
		jQuery(".twitter_friend").css("display", "none");
		jQuery(".twitter_friend[data-name^='"+value+"']").show();
	});

	jQuery(document).ready(function(){
		if(jQuery("#facebook_friends").length){
			download_facebook_friends();
		}
		if(jQuery("#twitter_friends").length){
			download_twitter_friends();
		}
	})

})(jQuery);
