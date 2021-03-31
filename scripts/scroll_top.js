$('#scroll-body').scroll(function(){
	if($(this).scrollTop() >= 50) {
		$('#return-to-top').fadeIn(200);
	} else {
		$('#return-to-top').fadeOut(200);
	}
});
$('#return-to-top').click(function(){
	$('#scroll-body').animate({scrollTop:0},200);
});