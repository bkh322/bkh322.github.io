<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

define("_DOCTYPE_", "STYLE");

include "./bbs/admin/admin.lib.php";

$menu = "menu997";

include "./bbs/admin/head.php";
// include "./forum/forum.cfg.php";

if ( !$ca_id ) {
		alert("ca_id 값이 넘어와야 됩니다.");
} else {
		// 카테고리에 따른 테이블 값을 불러온다( $w 값과는 상관없이 불러와야함
		$row = sql_fetch(" select * from {$write_table}_cat where ca_id = '$ca_id' ");
		if ( !$row[0] ) {
				alert("ca_id값이 없습니다.");
		}
}

if ( $w == u ) {		// 카테고리 수정

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
    
} else {						// 새 카테고리, 넘어온 ca_id값의 하위 카테고리를 만듬

		// 다음 ca_id
		$sql2 = " select max(ca_id) from {$write_table}_cat ";
		$row2 = sql_fetch($sql2);
		$ca_id = $row2[0] + 1;
		$ca_id_attr = "readonly style='background-color:#dddddd'";
    $ca_code_attr = "readonly style='background-color:#dddddd'";
		
		$ca_parent_code 	= $row[ca_code];
		$ca_parent_depth 	= $row[ca_depth];		
		$ca_depth = $ca_parent_depth + 1;
		if ( $ca_depth > 5 ) {
				alert("분류는 5단계까지만 설정할 수 있습니다.");
		}
		// 다음 ca_code 구하기
		$ca_code = get_next_code($write_table, $ca_parent_code, $ca_depth);
    $is_rock 				= 0;
}
?>
<!-- 현 분류 정보 -->
<table width=100% cellpadding=3 cellspacing=1 border=0>
<tr>
		<td>현 카테고리 : <?=$ca_depth?> 단계</td>
</tr>
<tr>
		<td>위치 : 
		<?
				$category_pos_str = get_cat_pos_str($write_table, $row[ca_code], "");
				
				if ($w == 'u') $category_pos_str .= " 수정";
				else 
				    $category_pos_str .= " > $ca_depth 단계 새 분류 입력";
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
		<td>이름</td>
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
