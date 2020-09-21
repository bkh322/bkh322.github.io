<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>

<header class="nav-down">
	<div class="nav">
		<ul>
			<li>
				<a href="http://gaehwa.co.kr/g5" class="sm-link sm-link_padding-bottom sm-link3 sm-link__label"><h2><i class="xi-home" style="font-size:18px;"></i> HOME</h2></a>
			</li>
			<!--<li>
				<a href="#" class="sm-link sm-link_padding-bottom sm-link3 sm-link__label"><h2>ABOUT ME</h2></a>
			</li>-->
			<li>
				<a href="http://gaehwa.co.kr/portfolio" class="sm-link sm-link_padding-bottom sm-link3"><h2 class="sm-link__label">PORTFOLIO</h2></a>
			</li>
			<li>
				<a href="http://gaehwa.co.kr/gallery" class="sm-link sm-link_padding-bottom sm-link3"><h2 class="sm-link__label">GALLERY</h2></a>
			</li>
			<li>
				<a href="http://gaehwa.co.kr/free" class="sm-link sm-link_padding-bottom sm-link3"><h2 class="sm-link__label">CODE</h2></a>
			</li>
		</ul>
	</div>
</header>
