<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

define("_DOCTYPE_", "NONE");

define("_GNUADMIN_", "DEFAULT");

include "./bbs/admin/admin.lib.php";

// �Խ��ǿ� �߰��� Į�� ����
$sql = " ALTER TABLE {$write_table} DROP ca_code";
sql_query($sql);

// �Խ��� �з��� �߰��� Į�� ����
$sql = " ALTER TABLE {$write_table}_cat DROP ca_code";
sql_query($sql);

$sql = " ALTER TABLE {$write_table}_cat DROP ca_parent_code";
sql_query($sql);

$sql = " ALTER TABLE {$write_table}_cat DROP ca_depth";
sql_query($sql);

$sql = " ALTER TABLE {$write_table}_cat DROP is_rowcat";
sql_query($sql);
echo "<span style='font-size:9pt;'>$bo_table �Խ����� ī�װ��� �������� �����Ǿ����ϴ�.</span><br>";
echo "<span style='font-size:9pt;'><a href='./?doc=bbs/admin/boardcategorylist.php&bo_table=$bo_table' class=tt>$bo_table �� �з� ����ȭ������</a></span>";
?>
