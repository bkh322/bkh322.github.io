<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

define("_DOCTYPE_", "NONE");

include "./bbs/admin/admin.lib.php";

if ($ca_admin) {
    $mb = get_member($ca_admin);
    if (!$mb[mb_id]) {
        alert("포럼 관리자 회원아이디가 존재하지 않습니다.");
    }
}

if ($w == 'u') {                

		$sql = " update {$write_table}_cat 
								set ca_name		= '$ca_name'
								where ca_id = '$ca_id' ";
		sql_query($sql);

} else {

		$sql = " insert into {$write_table}_cat 
								set	ca_id 					= '$ca_id',
										ca_code   			= '$ca_code',
										ca_parent_code	= '$ca_parent_code',
										ca_depth				= '$ca_depth',
										ca_name 				= '$ca_name',
										is_rowcat 		  = '0'
			 			";
		sql_query($sql);
		
		$sql = " select is_rowcat from {$write_table}_cat
								where ca_code = '$ca_parent_code'";
		$row = sql_fetch($sql);
		if($row[0] == '0') {
				sql_query(" update {$write_table}_cat set is_rowcat = '1' where ca_code = '$ca_parent_code'");
		}
		 
}

gotourl("./?doc=bbs/admin/board5categorylist.php&bo_table=$bo_table");


?>
