<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
include_once(G5_LIB_PATH.'/latest_group.lib.php');


?>
	<div class="content-wrap">
		<!-- Swiper -->
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div class="swiper-slide"><img src="<? echo G5_THEME_IMG_URL ?>/main_slide_01.png" alt="슬라이드이미지_01">
				</div>
				<div class="swiper-slide">
					<img src="<? echo G5_THEME_IMG_URL ?>/main_slide_02.png" alt="슬라이드이미지_02">
					<div class="slideTxt">
						<h3><em>배움</em>과 <em>새로움</em>을 두려워 하지 않는 인재.<br/>
							<em>팀원</em>들과 <em>소통</em>할 줄 아는 인재.<br/>
							<em>기획</em>과 <em>개발</em>에 이해도가 있는 인재.<br/>
							멀리서 찾지마세요.							
						</h3>
						
						</h4>
					</div>
				</div>
			</div>
			<!-- Add Pagination -->
			<div class="swiper-pagination"></div>
			<!-- Add Arrows -->
			<div class="swiper-button-next">
				<i class="xi-angle-right xi-3x"></i>
			</div>
			<div class="swiper-button-prev">
				<i class="xi-angle-left xi-3x"></i>
			</div>
		</div>

	</div><!--content-wrap-end-->
	<div class="main-content-01">
		<p>
			<span>포트폴리오 바로가기</span><span class="fr"><a href="http://gaehwa.co.kr/portfolio"><i class="xi-angle-right"></i></a></span>
		</p>
	</div>
	<div class="main-content-02">
		<h3><i class="xi-spinner-1 xi-spin"></i> HTML5 웹표준과 웹접근성을 이용한 홈페이지 구축</h3>
		<ul>
			<li  data-aos="fade-up"><img src="<? echo G5_THEME_IMG_URL ?>/html5_img.png" alt="html5"></li>
			<li  data-aos="zoom-out"><img src="<? echo G5_THEME_IMG_URL ?>/css_img.png" alt="css"></li>
			<li  data-aos="fade-up"><img src="<? echo G5_THEME_IMG_URL ?>/js_img.png" alt="js"></li>
		</ul>
	</div>
	
	<div class="main-content-03-wrap">
		<div class="main-content-03 contain">
		<? echo latest('theme/pic_list', 'gallery', 3, 23); ?>
		</div>
	</div><!--e-main-content-03-->
		<div class="main-content-04 contain pc">
			<? echo latest('theme/pic_block', 'portfolio', 4, 23); ?>
		</div><!--e-main-content-04-->
		<div class="main-content-04 contain mobile">
			<? echo latest('theme/pic_block', 'portfolio', 2, 23); ?>
		</div><!--e-main-content-04-->
	<script>
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('header').outerHeight();

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('header').removeClass('nav-down').addClass('nav-up');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('header').removeClass('nav-up').addClass('nav-down');
        }
    }
    
    lastScrollTop = st;
}
      AOS.init({
        easing: 'ease-in-out-sine'
      });	
		var swiper = new Swiper('.swiper-container', {
			spaceBetween : 30,
			centeredSlides : true,
	/*		autoplay : {
				delay : 3000,
				disableOnInteraction : false,
			},*/
			pagination : {
				el : '.swiper-pagination',
				clickable : true,
			},
			navigation : {
				nextEl : '.swiper-button-next',
				prevEl : '.swiper-button-prev',
			},
		});
	</script>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>