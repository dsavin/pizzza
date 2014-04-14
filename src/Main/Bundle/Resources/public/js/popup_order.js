$(document).ready(function() {
	$("#list-container").scrollbars();
	
	$("#popup-order-close").click(function(){
        $('#wrapper').show();
		$("#popup-order-overlay").css("display","none");
		$("#popup-order-wrap").css("display","none");
	});
});