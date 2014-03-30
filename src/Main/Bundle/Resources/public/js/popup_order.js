$(document).ready(function() {
	$("#list-container").scrollbars();
	
	$("#popup-order-close").click(function(){
		$("#popup-order-overlay").css("display","none");
		$("#popup-order-wrap").css("display","none");
	});
});