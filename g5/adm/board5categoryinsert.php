<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

define("_DOCTYPE_", "NONE");

include "./bbs/admin/admin.lib.php";

// 다음 ca_id 값 구하기
$sql = " select max(ca_id) from {$write_table}_cat ";
$row = sql_fetch($sql);

// if (!$row[0]) alert("분류가 없습니다");

$next_ca_id = $row[0] + 1;

// 다음 ca_code 구하기
// $next_ca_code = get_next_code('0', '1');

$sql2 = " select max(ca_code) from {$write_table}_cat where ca_parent_code = '0' ";
$row2 = sql_fetch($sql2); 

$next_ca_code = $row2[0] + 100000000;

// test 구문
// echo $row2[0];
// echo $next_ca_code;

if( $next_ca_code >= 9900000000 ) {
		alert("더 이상 분류값을 추가하실 수 없습니다.");
}

$sql3 = " insert into {$write_table}_cat 
						set	ca_id 					= '$next_ca_id',
								ca_code   			= '$next_ca_code',
								ca_parent_code	= '0',
								ca_depth				= '1',
								ca_name 				= '$ca_name'
			 ";
sql_query($sql3);

gotourl("./?doc=bbs/admin/board5categorylist.php&bo_table=$bo_table");
?>
