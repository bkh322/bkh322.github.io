<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

define("_DOCTYPE_", "STYLE");

include "./bbs/admin/admin.lib.php";

$sql = " select * from {$write_table}_cat order by ca_id asc ";
$result = sql_query($sql);

// 5단계분류 일경우 5단계분류 전용 관리창으로 이동 by john2karl 2004-12-27 11:07:00
$is_5cat = is_5depthcategory($write_table);
if ($is_5cat) {
		gotourl("./?doc=bbs/admin/board5categorylist.php&bo_table=$bo_table");		
}else {
include "./bbs/admin/head.php";
?>

<table width=450 align=center><tr><td>

<div align=right>※ 삭제시 게시물은 1번 분류로 변경됩니다. <a href='./?doc=install/installgnu5depthcategory.php&bo_table=<?=$bo_table?>' class=tt>5단계분류로 확장</a></div>

<table width=100% cellpadding=3 cellspacing=1>
<form name=fcategorylist method=post action='<?="./?doc=bbs/admin/boardcategoryupdate.php"?>'>
<input type=hidden name=bo_table value='<?=$bo_table?>'>
<colgroup width=50 class=th>
<colgroup width='' class=th>
<colgroup width=50 class=th>
<colgroup width=50 class=th>
<tr align=center height=27>
    <td>ID</td>
    <td>분류명</td>
    <td>글수</td>
    <td>삭제</td>
</tr>
</table>
<table width=100% cellpadding=3 cellspacing=1>
<?
for ($i=0; $row=mysql_fetch_array($result); $i++) {
    $del = "&nbsp;";
    if ($i > 0) {
        //$del = "<input type=image src='bbs/admin/image/icon_del.gif' border=0 onclick=\"if (confirm('정말 삭제하시겠습니까?\\n\\n삭제후에는 자료를 복구할 수 없습니다.\\n\\n삭제시 이 분류에 포함된 게시물은 1번 분류로 변경됩니다.')) { location='./?doc=bbs/admin/boardcategorydelete.php&bo_table=$bo_table&ca_id=$row[ca_id]'; }\">";
        $del = "<a href=\"javascript:if (confirm('정말 삭제하시겠습니까?\\n\\n삭제후에는 자료를 복구할 수 없습니다.\\n\\n삭제시 이 분류에 포함된 게시물은 1번 분류로 변경됩니다.')) { location='./?doc=bbs/admin/boardcategorydelete.php&bo_table=$bo_table&ca_id=$row[ca_id]'; }\"><img src='bbs/admin/image/icon_del.gif' border=0></a>";
    }

    $sql1 = " select count(*) from $write_table 
               where ca_id = '$row[ca_id]'
                 and wr_comment = '0' ";
    $row1 = sql_fetch($sql1);
    $cnt = (int)$row1[0];

    $list = $i%2;
    echo "
    <tr class='list$list ht' align=center>
        <input type=hidden name=ca_id[$i] value='$row[ca_id]'>
        <td width=50>$row[ca_id]</td>
        <td width=''><input type=text name=ca_name[$i] value='$row[ca_name]' size=40 required itemname='분류명' class=ib></td>
        <td width=50>$cnt</td>
        <td width=50>$upd$del</td>
    </tr>";
} 

if ($i == 0) { 
    echo "<tr><td colspan=4 align=center height=100 class='content contentbg'>자료가 없습니다.</td></tr>"; 
}
?>

<tr>
    <td colspan=4 align=center height=30 bgcolor=#ffffff>
        <input type=image src='bbs/admin/image/btn_alledit.gif'>
        <a href='<?="./?doc=bbs/admin/boardform.php&w=u&bo_table=$bo_table"?>'><img src='bbs/admin/image/btn_edit.gif' border=0></a>
        <a href='<?="./?doc=bbs/gnuboard.php&bo_table=$bo_table"?>'><img src='bbs/admin/image/btn_preview.gif' border=0></a>
    </td>
</tr>
</form>
</table>

<p>
<br>
<table width=100% cellpadding=4 cellspacing=1 class=tablebg>
<form name=fcategory method='post' action='<?="./?doc=bbs/admin/boardcategoryinsert.php"?>'>
<input type=hidden name=bo_table value='<?=$bo_table?>'>
<tr>
    <td align=center bgcolor=#ffffff>분류명 : 
        <input type=text name=ca_name class=ib size=40 required itemname='분류명'>
        <input type=image src='bbs/admin/image/btn_add_cat.gif' align=absmiddle>
    </td>
</tr>
</form>
</table>

</td></tr></table>

<?
include "./bbs/admin/tail.php";
} // if문
?>
