// JavaScript Document
$(document).ready(function(){

    var nav = $('.nav_ex');
    var navoffset = $('.nav_ex').offset();  
    $(window).scroll(function () {
        if ($(this).scrollTop() >= navoffset.top) {
         nav.css('position','fixed').css('top',0);
        }else {
            nav.css('position','absolute').css('top',240);
        }
		if($(this).scrollTop()>100){
		$(".aside_top").fadeIn("fast");	
		}else{
		$(".aside_top").fadeOut("fast");	
			}
		if($(this).scrollTop()>100){
		$(".aside_bottom").fadeIn("fast");	
		}else{
		$(".aside_bottom").fadeOut("fast");	
			}			
    });
	
			$(".aside_top").on("click", function(){
				$("html,body").stop().animate({
					scrollTop:0},1000);
		});
            $('.aside_bottom').click(function() {

                var b = $('html').height();
                $('body,html').animate({
                    scrollTop: b},1000);

            });           

});
