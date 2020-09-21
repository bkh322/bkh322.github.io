<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

<div class="lat">
    <h2 class="lat_title"><a href="<?php echo G5_BBS_URL ?>/new.php?gr_id=<?php echo $gr_id ?>"><?php echo $gr_subject; ?></a></h2>
    <ul>
    <?php for ($i=0; $i<count($list); $i++) { ?>
        <li>
            <?php
            if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i><span class=\"sound_only\">비밀글</span> ";
            if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">N<span class=\"sound_only\">새글</span></span>";
            if ($list[$i]['icon_hot']) echo "<span class=\"hot_icon\">H<span class=\"sound_only\">인기글</span></span>";
 
            echo "<a href=\"".$list[$i]['href']."\"> ";
            echo "[".$list[$i]['bo_subject']."] ";
            echo $list[$i]['subject'];
            echo "</a>";

            if ($list[$i]['comment_cnt']) echo " <span class=\"lt_cmt\">+ ".$list[$i]['comment_cnt']."</span>";
            ?>
            <span class="lt_date"><?php echo $list[$i]['datetime2'] ?></span>
        </li>
    <?php } ?>
    <?php if (count($list) == 0) { //게시물이 없을 때 ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php } ?>
    </ul>
    <a href="<?php echo G5_BBS_URL ?>/new.php?gr_id=<?php echo $gr_id ?>" class="lt_more"><span class="sound_only"><?php echo $gr_subject; ?></span><i class="fa fa-plus" aria-hidden="true"></i><span class="sound_only"> 더보기</span></a>
</div>
