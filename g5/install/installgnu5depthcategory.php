<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

if ($is_admin != "default") {
		alert("�����ڸ��� ��ġ�� �� �ֽ��ϴ�.");
}

if ( !$bo_table ) {
		alert("bo_table ���� �Էµ��� �ʾҽ��ϴ�."); 
} 

// �״����� �Խ��ǿ� Į�� �߰�
$sql = "ALTER TABLE {$write_table} ADD ca_code INT( 11 ) NOT NULL AFTER ca_id ";
sql_query($sql);

// �״����� �Խ��� �з��� Į���� �߰�

$sql = "ALTER TABLE {$write_table}_cat ADD ca_code INT( 11 ) NOT NULL AFTER ca_id ";
sql_query($sql);

$sql = "ALTER TABLE {$write_table}_cat ADD ca_parent_code INT( 11 ) NOT NULL AFTER ca_code ";
sql_query($sql);

$sql = "ALTER TABLE {$write_table}_cat ADD ca_depth INT( 11 ) NOT NULL AFTER ca_parent_code ";
sql_query($sql);

$sql = "ALTER TABLE {$write_table}_cat ADD is_rowcat INT ( 11 ) NOT NULL AFTER ca_depth ";
sql_query($sql);

// �״����� �з����� ���� �ִ� ���鿡 ca_code, ca_parent_code, ca_depth ���� �ֱ�
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
