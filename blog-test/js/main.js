$(document).ready(function(){

	var a = window.location.href.split("/");
    var b = a.slice(0,-1);
    var path = b.join("/");
	
	$("#Post1-btn").click(function(){
		window.location.href=path+"/post-1.html";
	});
	
	$("#Post2-btn").click(function(){
		window.location.href=path+"/post-2.html";
	});
	
	
	$("")
});