<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

define("_DOCTYPE_", "NONE");

include "./bbs/admin/admin.lib.php";

// forum 에 게시물이 있으면
$sql = "select count(*) from $write_table where ca_id = '$ca_id' ";
$row = sql_fetch($sql);
$cnt = (int)$row[0];
			
if ($cnt != 0) {
		alert("forum에 ca_id값을 가진 게시물이 $cnt 개 있습니다. ");
}
		
// 하위 분류가 있다면
$sql1 = " select count(*) from {$write_table}_cat where ca_parent_code = '$ca_code' ";
$row1 = sql_fetch($sql1);
$cnt1 = (int)$row1[0];
			
if ($cnt1 != 0) {
		alert("하위 분류를 먼저 지워주세요. ");
}

// 지울려는 분류의 부모 분류를 구한다.
$sql2 = " select ca_parent_code from {$write_table}_cat where ca_id = '$ca_id' ";
$row2 = sql_fetch($sql2);
// echo $row2[0];
// 삭제  
$sql5 = " delete from {$write_table}_cat where ca_id = '$ca_id' ";
sql_query($sql5);


$sql3 = " select count(*) from {$write_table}_cat where ca_parent_code = '$row2[0]' ";
$row3 = sql_fetch($sql3);
//echo "몇개". $row3[0];
// 부모 분류의 is_rowcat을 0으로 바꿈
if (!$row3[0]) {
		$sql4 = " select is_rowcat from {$write_table}_cat
								where ca_code = '$row2[0]'";
		$row4 = sql_fetch($sql4);
		// echo "is_rocat의 값".$row4[0];
		if($row4[0] == '1') {
				sql_query(" update {$write_table}_cat set is_rowcat = '0' where ca_code = '$row2[0]'");
		}
}
gotourl("./?doc=bbs/admin/board5categorylist.php&bo_table=$bo_table");
?>
