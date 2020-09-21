<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

define("_DOCTYPE_", "NONE");

define("_GNUADMIN_", "DEFAULT");

include "./bbs/admin/admin.lib.php";

// 게시판에 추가된 칼럼 삭제
$sql = " ALTER TABLE {$write_table} DROP ca_code";
sql_query($sql);

// 게시판 분류에 추가된 칼럼 삭제
$sql = " ALTER TABLE {$write_table}_cat DROP ca_code";
sql_query($sql);

$sql = " ALTER TABLE {$write_table}_cat DROP ca_parent_code";
sql_query($sql);

$sql = " ALTER TABLE {$write_table}_cat DROP ca_depth";
sql_query($sql);

$sql = " ALTER TABLE {$write_table}_cat DROP is_rowcat";
sql_query($sql);
echo "<span style='font-size:9pt;'>$bo_table 게시판의 카테고리가 원본으로 수정되었습니다.</span><br>";
echo "<span style='font-size:9pt;'><a href='./?doc=bbs/admin/boardcategorylist.php&bo_table=$bo_table' class=tt>$bo_table 의 분류 관리화면으로</a></span>";
?>
