<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

define("_DOCTYPE_", "STYLE");

include "./bbs/admin/admin.lib.php";

$menu = "menu997";

include "./bbs/admin/head.php";
// include "./forum/forum.cfg.php";

if ( !$ca_id ) {
		alert("ca_id ���� �Ѿ�;� �˴ϴ�.");
} else {
		// ī�װ��� ���� ���̺� ���� �ҷ��´�( $w ������ ������� �ҷ��;���
		$row = sql_fetch(" select * from {$write_table}_cat where ca_id = '$ca_id' ");
		if ( !$row[0] ) {
				alert("ca_id���� �����ϴ�.");
		}
}

if ( $w == u ) {		// ī�װ� ����

		$ca_id = $_GET[ca_id];
		$ca_id_attr = "readonly style='background-color:#dddddd'";
    $ca_parent_code = $row[ca_parent_code];
    $ca_code 				= $row[ca_code];
    $ca_code_attr = "readonly style='background-color:#dddddd'";
    $ca_depth				= $row[ca_depth];
    $ca_name 				= $row[ca_name];
    $ca_explain 		= $row[ca_explain];
    $is_rock 				= 0;
    if ( $row[is_rock] ) {
    		$is_rock = $row[is_rock];
    }
    $ca_admin 			= $row[ca_admin];
    
} else {						// �� ī�װ�, �Ѿ�� ca_id���� ���� ī�װ��� ����

		// ���� ca_id
		$sql2 = " select max(ca_id) from {$write_table}_cat ";
		$row2 = sql_fetch($sql2);
		$ca_id = $row2[0] + 1;
		$ca_id_attr = "readonly style='background-color:#dddddd'";
    $ca_code_attr = "readonly style='background-color:#dddddd'";
		
		$ca_parent_code 	= $row[ca_code];
		$ca_parent_depth 	= $row[ca_depth];		
		$ca_depth = $ca_parent_depth + 1;
		if ( $ca_depth > 5 ) {
				alert("�з��� 5�ܰ������ ������ �� �ֽ��ϴ�.");
		}
		// ���� ca_code ���ϱ�
		$ca_code = get_next_code($write_table, $ca_parent_code, $ca_depth);
    $is_rock 				= 0;
}
?>
<!-- �� �з� ���� -->
<table width=100% cellpadding=3 cellspacing=1 border=0>
<tr>
		<td>�� ī�װ� : <?=$ca_depth?> �ܰ�</td>
</tr>
<tr>
		<td>��ġ : 
		<?
				$category_pos_str = get_cat_pos_str($write_table, $row[ca_code], "");
				
				if ($w == 'u') $category_pos_str .= " ����";
				else 
				    $category_pos_str .= " > $ca_depth �ܰ� �� �з� �Է�";
				echo $category_pos_str;
		?>
		</td>
</tr>
</table>

<table width=100% cellpadding=3 cellspacing=1 class=tablebg>
<form name=fcategory method=post action='<?="./?doc=bbs/admin/board5categoryupdate.php"?>'>
<input type=hidden name=w								value='<?=$w?>'>
<input type=hidden name=bo_table				value='<?=$bo_table?>'>
<input type=hidden name=ca_parent_code 	value='<?=$ca_parent_code?>'>
<input type=hidden name=ca_depth 				value='<?=$ca_depth?>'>
<colgroup width=115 class='subject subjectbg'>
<colgroup width=655 class='content contentbg'>
<tr>
    <td>ID</td>
    <td><input type=text class=ib name=ca_id size=20 maxlength=20 <?=$ca_id_attr?> itemname='ca_id' value='<?=$ca_id?>'>
		</td>
</tr>
<tr>
		<td>CODE</td>
		<td><input type=text class=ib name=ca_code size=20 maxlength=20 <?=$ca_code_attr?> itemname='ca_code' value='<?=$ca_code?>'>
		</td>
</tr>
<tr>
		<td>�̸�</td>
		<td><input type=text class=ib name=ca_name size=60 required itemname='ca_name' value='<?=$ca_name?>'></td>
</tr>
</table>
<p>
<div align=center>
    <input type=image src='bbs/admin/image/btn_confirm2.gif'>
</div>
<script language='JavaScript'>
		var f = document.fcategory;
		if (f.is_rock.value) {
				f.is_rock.value = '<? echo $is_rock; ?>';
		}
</script>
<?
include "./bbs/admin/tail.php";
?>
