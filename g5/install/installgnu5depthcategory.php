<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

if ($is_admin != "default") {
		alert("관리자만이 설치할 수 있습니다.");
}

if ( !$bo_table ) {
		alert("bo_table 값이 입력되지 않았습니다."); 
} 

// 그누보드 게시판에 칼럼 추가
$sql = "ALTER TABLE {$write_table} ADD ca_code INT( 11 ) NOT NULL AFTER ca_id ";
sql_query($sql);

// 그누보드 게시판 분류에 칼럼을 추가

$sql = "ALTER TABLE {$write_table}_cat ADD ca_code INT( 11 ) NOT NULL AFTER ca_id ";
sql_query($sql);

$sql = "ALTER TABLE {$write_table}_cat ADD ca_parent_code INT( 11 ) NOT NULL AFTER ca_code ";
sql_query($sql);

$sql = "ALTER TABLE {$write_table}_cat ADD ca_depth INT( 11 ) NOT NULL AFTER ca_parent_code ";
sql_query($sql);

$sql = "ALTER TABLE {$write_table}_cat ADD is_rowcat INT ( 11 ) NOT NULL AFTER ca_depth ";
sql_query($sql);

// 그누보드 분류값에 원래 있던 값들에 ca_code, ca_parent_code, ca_depth 값을 넣기
$sql = "select * from {$write_table}_cat order by ca_id";
$result = sql_query($sql);

for( $i=0; $row=mysql_fetch_array($result); $i++ ) {

		$category_code = $row[ca_id] * 100000000;
		$sql2 = " update {$write_table}_cat
								set ca_code 				= '$category_code',
										ca_parent_code 	= '0',
										ca_depth				= '1'  
								where ca_id = '$row[ca_id]'
						";
		sql_query($sql2);
		
}
gotourl("./?doc=bbs/admin/boardcategorylist.php&bo_table=$bo_table");
?>
