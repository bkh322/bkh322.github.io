<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

define("_DOCTYPE_", "NONE");

include "./bbs/admin/admin.lib.php";

// forum �� �Խù��� ������
$sql = "select count(*) from $write_table where ca_id = '$ca_id' ";
$row = sql_fetch($sql);
$cnt = (int)$row[0];
			
if ($cnt != 0) {
		alert("forum�� ca_id���� ���� �Խù��� $cnt �� �ֽ��ϴ�. ");
}
		
// ���� �з��� �ִٸ�
$sql1 = " select count(*) from {$write_table}_cat where ca_parent_code = '$ca_code' ";
$row1 = sql_fetch($sql1);
$cnt1 = (int)$row1[0];
			
if ($cnt1 != 0) {
		alert("���� �з��� ���� �����ּ���. ");
}

// ������� �з��� �θ� �з��� ���Ѵ�.
$sql2 = " select ca_parent_code from {$write_table}_cat where ca_id = '$ca_id' ";
$row2 = sql_fetch($sql2);
// echo $row2[0];
// ����  
$sql5 = " delete from {$write_table}_cat where ca_id = '$ca_id' ";
sql_query($sql5);


$sql3 = " select count(*) from {$write_table}_cat where ca_parent_code = '$row2[0]' ";
$row3 = sql_fetch($sql3);
//echo "�". $row3[0];
// �θ� �з��� is_rowcat�� 0���� �ٲ�
if (!$row3[0]) {
		$sql4 = " select is_rowcat from {$write_table}_cat
								where ca_code = '$row2[0]'";
		$row4 = sql_fetch($sql4);
		// echo "is_rocat�� ��".$row4[0];
		if($row4[0] == '1') {
				sql_query(" update {$write_table}_cat set is_rowcat = '0' where ca_code = '$row2[0]'");
		}
}
gotourl("./?doc=bbs/admin/board5categorylist.php&bo_table=$bo_table");
?>
