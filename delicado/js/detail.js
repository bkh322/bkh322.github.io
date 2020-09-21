// JavaScript Document
$(function(){
	$(".detail_btn_01").on("mouseover",function(){
		$("#frame").removeClass().addClass("detail_01");
	});
	$(".detail_btn_01").on("mouseout",function(){
		$("#frame").removeClass().addClass("detail_01");
	});	
	
	$(".detail_btn_02").on("mouseover",function(){
		$("#frame").removeClass().addClass("detail_02");
	});
	$(".detail_btn_02").on("mouseout",function(){
		$("#frame").removeClass().addClass("detail_01");
	});		
	
	$(".detail_btn_03").on("mouseover",function(){
		$("#frame").removeClass().addClass("detail_03");
	});
	$(".detail_btn_03").on("mouseout",function(){
		$("#frame").removeClass().addClass("detail_01");
	});		
	$(".detail_btn_04").on("mouseover",function(){
		$("#frame").removeClass().addClass("detail_04");
	});
	$(".detail_btn_04").on("mouseout",function(){
		$("#frame").removeClass().addClass("detail_01");
	});		
	
		
	var menu=$(".img_nav>ul>li");
	
	menu.on({
      mouseover:function(){
		  var target=$(this);
		  target.addClass("on")
		  },
	   mouseout:function(){
		   var target=$(this);
		   target.removeClass("on")
		   }	  
		})
	
	
});

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

                var b = $('body').height();
                $('body,html').animate({
                    scrollTop: b},1000);

            });           

});
