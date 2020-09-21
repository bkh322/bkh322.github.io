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
    });
});
