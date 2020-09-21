<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}
?>

   	<div class="aside_top">
		<i class="xi-angle-up-thin xi-2x"></i>
	</div>
	<footer>
		<p>이 사이트는 포트폴리오용으로 제작되었으며 상업적인 용도로 사용할 수 없습니다.</p>
		<p class="footer-bottom">
			COPYRIGHT (C) BackGaehwa.ALL RIGHTS RESERVED.
		</p>
	</footer>
    <button type="button" id="top_btn">
    	<i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span>
    </button>
    <script>
    $(function() {
        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });
    });
    </script>
</div>

<?php
if(G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
function blockRightClick(){
    alert("오른쪽 버튼은 사용할 수 없습니다.");
    return false;
}
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>