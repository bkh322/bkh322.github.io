<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

define("_DOCTYPE_", "NONE");

include "./bbs/admin/admin.lib.php";

// ���� ca_id �� ���ϱ�
$sql = " select max(ca_id) from {$write_table}_cat ";
$row = sql_fetch($sql);

// if (!$row[0]) alert("�з��� �����ϴ�");

$next_ca_id = $row[0] + 1;

// ���� ca_code ���ϱ�
// $next_ca_code = get_next_code('0', '1');

$sql2 = " select max(ca_code) from {$write_table}_cat where ca_parent_code = '0' ";
$row2 = sql_fetch($sql2); 

$next_ca_code = $row2[0] + 100000000;

// test ����
// echo $row2[0];
// echo $next_ca_code;

if( $next_ca_code >= 9900000000 ) {
		alert("�� �̻� �з����� �߰��Ͻ� �� �����ϴ�.");
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
