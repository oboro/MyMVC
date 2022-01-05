$(document).ready(function(){

//topへボタン
    $('#goTop').hide();

	$(window).scroll(function(){

		var now = $(window).scrollTop();

		var under = $('body').height() - (now + $(window).height());
 
		if( now > 700 ){
			$('#goTop').fadeIn('slow');
		}else{
			$('#goTop').stop(true).fadeOut('slow'); //
		}
	});
 
	$('#goTop').click(function(){
		$('body,html').animate({scrollTop:0},'slow');
		return false;
	});    
  
//SP用MENUスライド
$(".spMenuBtn").click(function () {
  $(this).next("nav").slideToggle();
  $("header.layout").toggleClass( "open");
});
  
//汎用トグル
$(".toggle").click(function () {
  $(this).next("div").slideToggle();
  $(this).toggleClass( "open");
});
  
  });