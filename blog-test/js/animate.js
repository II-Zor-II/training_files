$(document).ready(function(){
	

	$(".post-img-next, .post-img-prev").click(function(){
		$(".current,.next").toggle();	
	});

	$(".share-social").find(".fa").hover(function(){
		$(this).animate({padding:"15px"});
	},function(){
		$(this).animate({padding:"10px"});
	});
	
	$(".continue-btn").hover(function(){
		$(this).animate({padding:"15px"},600).css({
			"background-color":"white",
			"color":"black",
			"font-size":"initial"
		});
	},function(){
		$(this).animate({padding:"12px"},800).css({
			"background-color":"black",
			"color":"white",
			"font-size":"initial"
		});
	});
	
});