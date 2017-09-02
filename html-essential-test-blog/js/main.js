$(document).ready(function(){
	var meter = $("#meter");
	var i = Number(meter.val());
	console.log(i);
	setInterval(function(){
		i = i + 0.01;
		meter.val(i);
		if(i>1){
			i = 0;
		}
	},50);
//
//	$("#Submit").click(function(e){
//		e.preventDefault();
//		var inputName = $("#name-input").val();
//		var inputEmail = $("#e-mail").val();
//		var inputMsg = $("#msg").val();
//		alert(
//		 "Your Form says\n\n" +
//		 "FirstName: " + inputName + "\nEmail: " + inputEmail + "\nMessage: " + inputMsg
//		);
//	});
//	
});

	
function formFunction(){
	var inputName = $("#name-input").val();
	var inputEmail = $("#e-mail").val();
	var inputMsg = $("#msg").val();
	alert(
	 "Your Form says\n\n" +
	 "FirstName: " + inputName + "\nEmail: " + inputEmail + "\nMessage: " + inputMsg
	);
}