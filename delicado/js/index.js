// JavaScript Document

	function on(obj){obj.src=obj.src.replace('_off.jpg','_on.jpg');}
	function off(obj){obj.src=obj.src.replace('_on.jpg','_off.jpg');}
	
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
		
var imgarr = new Array();
var urlarr = new Array();




imgarr[0] = "img/main.jpg";    // 슬라이더 이미지
urlarr[0] = "index.html";  // 메뉴와 이미지 클릭시 이동할 주소


imgarr[1] = "img/main_02.gif";
urlarr[1] = "index.html";


imgarr[2] = "img/main_03.gif";
urlarr[2] = "index.html";




var auto_num = 0;
var auto_start;
var auto_time = 2000;    // 이미지 바뀌는 시간설정 - 초




$(function(){
$(".visual_main").prepend("<img id='slide_img' src='"+imgarr[0]+"' alt='0' />");
$(".visual_main li a:first").css({"backgroundColor":"black"});
$(".visual_main li a").each(function(i) { 
$(this).attr("idx", i);
}).click(function() {
location.href=urlarr[$(this).attr("idx")];
}).hover(function(){
clearInterval(auto_start);
$(".visual_main li a").css({"backgroundColor":"white"});
$(this).css({"backgroundColor":"black"});
$("#slide_img").attr("src",imgarr[$(this).attr("idx")]);
$("#slide_img").attr("alt",$(this).attr("idx"));
},function(){
auto_num = $(this).attr("idx");
auto_start = setInterval("auto()",auto_time);;
});
$("#slide_img").click(function() {
location.href=urlarr[$(this).attr("alt")];
});
auto_start = setInterval("auto()",auto_time);
});
function auto(){
auto_num++;
if(auto_num >= imgarr.length){ auto_num = 0; }
$(".visual_main li a").css({"backgroundColor":"white"});
$(".visual_main li a").each(function(i) {
if(auto_num == i){
$(this).css({"backgroundColor":"black"});
$("#slide_img").attr("src",imgarr[$(this).attr("idx")]);
$("#slide_img").attr("alt",$(this).attr("idx"));
}
});
}
