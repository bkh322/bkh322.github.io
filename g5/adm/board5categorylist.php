<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

define("_DOCTYPE_", "STYLE");

include "./bbs/admin/admin.lib.php";

$is_5cat = is_5depthcategory($write_table);
if(!$is_5cat) {
		alert("5�ܰ�з��� ����ϴ� �Խ��Ǹ� �̿��� �� �ֽ��ϴ�.");
}
$sql = " select * from {$write_table}_cat order by ca_code asc ";
$result = sql_query($sql);

include "./bbs/admin/head.php";
?>

<table width=600 align=center><tr><td>

<div align=right>�� ������ �Խù��� 1�� �з��� ����˴ϴ�.<br>
<?
		$is = is_5depthcategory($write_table);
		if ( $is ) {
				echo "<a href='./?doc=bbs/admin/gnu5depthcategorydelete.php&bo_table=$bo_table' class=tt>���� �з��� ��ȯ</a>";
		}
?>
</div>
<table width=100% cellpadding=3 cellspacing=1>
<colgroup width=50  class=th>
<colgroup width=100 class=th>
<colgroup width=''  class=th>
<colgroup width=50  class=th>
<colgroup width=100 class=th>
<tr align=center height=27>
    <td>ID</td>
    <td>CODE</td>
    <td>�з���</td>
    <td>�ۼ�</td>
    <td>����</td>
</tr>
</table>

<table width=100% cellpadding=3 cellspacing=1>
<?
for($i=0; $row=mysql_fetch_array($result); $i++) {

		$category_depth_str = get_depth_str ($row[ca_depth]);
		if ($row[ca_depth] != 1 ) { $category_depth_str .= "<img src='./bbs/admin/image/icon_catlevel.gif'>"; }
		
		$upd = "&nbsp;";
		$del = "&nbsp;";
		$add = "&nbsp;";
		$up = "&nbsp;";
		$dow = "&nbsp;";
		
		$upd = "<a href='./?doc=bbs/admin/board5categoryform.php&w=u&bo_table=$bo_table&ca_id=$row[ca_id]'><img src='./bbs/admin/image/icon_edit.gif' border=0 alt='����'></a>";
		$del .= "<a href='./?doc=bbs/admin/board5categorydelete.php&bo_table=$bo_table&ca_id=$row[ca_id]&ca_code=$row[ca_code]'><img src='./bbs/admin/image/icon_del.gif' border=0 alt='����'></a>";
		$add .= "<a href='./?doc=bbs/admin/board5categoryform.php&bo_table=$bo_table&ca_id=$row[ca_id]'><img src='./bbs/admin/image/icon_add.gif' border=0 alt='�߰�'></a>";
		// $up  .= "<img src='./bbs/admin/image/icon_up.gif' border=0 alt='����'>&nbsp;";
		// $dow .= "<img src='./bbs/admin/image/icon_dow.gif' border=0 alt='�Ʒ���'>";
		$list = $i%2;
		
		// �Խù��� ����
		$sql_ca_code = get_sql_category_code($write_table, $row[ca_code], '');
    $sql_count = " select distinct wr_parent_id from $write_table where $sql_ca_code";
    $result_count = sql_query($sql_count);
    $count = mysql_num_rows($result_count);
    echo "
    <tr class='list$list ht' align=center>
    		<td width=50>$row[ca_id]</td>
    		<td width=100>$row[ca_code]</td>
    		<td width='' align=left>$category_depth_str $row[ca_name]</td>
    		<td width=50>$count</td>
    		<td width=100>$upd$del$add$up$dow</td>
    </tr>";
}

if ($i == 0) { 
    echo "<tr><td colspan=5 align=center height=100 class='content contentbg'>�ڷᰡ �����ϴ�.</td></tr>"; 
}
?>
<tr>
    <td colspan=5 align=center height=30 bgcolor=#ffffff>
        <a href='<?="./?doc=bbs/admin/boardform.php&w=u&bo_table=$bo_table"?>'><img src='bbs/admin/image/btn_edit.gif' border=0></a>
        <a href='<?="./?doc=bbs/gnuboard.php&bo_table=$bo_table"?>'><img src='bbs/admin/image/btn_preview.gif' border=0></a>
    </td>
</tr>
</table>

<p>
<br>
<table width=100% cellpadding=4 cellspacing=1 class=tablebg>
<form name=fcategory method='post' action='<?="./?doc=bbs/admin/board5categoryinsert.php"?>'>
<input type=hidden name=bo_table value='<?=$bo_table?>'>
<tr>
    <td align=center bgcolor=#ffffff>1�ܰ� �з��� : 
        <input type=text name=ca_name class=ib size=40 required itemname='�з���'>
        <input type=image src='bbs/admin/image/btn_add_cat.gif' align=absmiddle>
    </td>
</tr>
</form>
</table>

</td></tr></table>

<?
include "./bbs/admin/tail.php";
?>
