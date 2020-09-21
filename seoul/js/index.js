// JavaScript Document

$(function(){
	
	var eleWidth=$(".main_visual>ul>li").innerWidth();
	var state=false; //선택안됐을때
	var playOn=false;
	var direction="left";
	var bannerAuto; //롤링될때 자동으로 실행시키기위한 변수
	
	function play(){
		if(!playOn){//플레이되고있지않다면
			playOn=true; //플레이온실행
			bannerAuto=setInterval(function(){ //setInterval 자동롤링함수
				if(direction=="left"){
					$(".main_visual_left_btn").click();
				} else {
					$(".main_visual_right_btn").click();
				}
			},3000);
		}
		if(playOn){
			$(".main_visual>ul>li>a").mouseover(function(){
				stop();
			});
		}		
	}
	
	function stop(){
		if(playOn){
			playOn=false; //선택안됐을때
			clearInterval(bannerAuto);
			$(".main_visual>ul>li>a").mouseout(function(){
				play();
			});
			$(".main_visual>ul>li>a").click(function(){
				location.href;
			});			
		}
	}
	
	function left(){
		stop(); //rolling
		direction="left";
		$(".main_visual>ul").animate({left:eleWidth*-1},
		500,
		function(){
			$(this).children("li:first").insertAfter($(this).children("li:last"));
			//첫번째있는것을 마지막 뒤로 붙힘
			$(this).css("left",0);
			state=false;
			play(); //rolling
		});
	}
	
	function right(){
		stop(); //rolling
		direction="right";
		$(".main_visual>ul>li:last").insertBefore($(".main_visual>ul>li:first"));
		$(".main_visual>ul").css("left",eleWidth*-1);
		$(".main_visual>ul").animate({left:0},500,
		function(){state=false;
		play();
		});
	}
	
	$(".main_visual_left_btn").click(function(){
		if(!state){
			state=true;
			left();
		}
	});
	
	$(".main_visual_right_btn").click(function(){
		if(!state){
			state=true;
			right();
		}
	});
	
	$(".main_visual_stop_btn").click(function(){
		stop();
	});
	
	$(".main_visual_play_btn").click(function(){
		play();
	});
	
	$(".aside_top").click()
	
			$(".aside_top").on("click", function(){
				$("html,body").stop().animate({
					scrollTop:0},1000);
		});	
	
});