<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
// 3.24-1
if (!defined("_GNUBOARD_INC_")) exit; // ���� ������ ���� �Ұ� 

// $ca_code�� $board[bo_1]���� ���ÿ� ������ �����з����� �˻� by john2karl 2004-12-30 21:39:00
// $ca_code�� �ִٸ� by john2karl 2004-12-26 19:02:00
if ($ca_code && $board[bo_1]) {
    $sql_ca_code = get_sql_category_code($write_table, $ca_code, "1");
} else if ($ca_code && !$board[bo_1]) {
    $sql_ca_code = "and ca_code = '$ca_code'";
}
// �з� ��� ����
$is_category = false;
if ($board[bo_use_category]) {
    $is_category = true;
    $category_location = "./?doc=bbs/gnuboard.php&bo_table=$bo_table&sselect=ca_id&stext=";
    // SELECT OPTION �±׷� �Ѱܹ���
    $category_option = get_category_option($write_table);
}

// �˻�� �ִٸ�
if ('' != $stext) {
    $sql_search = get_sql_search($sselect, $stext, $soperator);

    // ���� ���� ��ȣ�� �� ������ ���� (�ϴ��� ����¡���� ���)
    $sql = " select min(wr_num) from $write_table ";
    $row = sql_fetch($sql);
    $min_spart = $row[0];
    if (!$spart) {
        $spart = $min_spart;
    }

    $sql_search .= " and (wr_num between '".$spart."' and '".($spart+$cfg[search_spart])."') ";

    // �ش� �з��� $total_count�� ���ϱ� ���ؼ� $sql_ca_code���� �߰� by john2karl 2004-12-27 07:59:00
    // ���۸� ��´�. (�ڸ�Ʈ�� ���뵵 �˻��ϱ� ����)
    $sql = " select distinct wr_parent_id from $write_table where (1) $sql_search $sql_ca_code";
    $result = sql_query($sql);
    $total_count = mysql_num_rows($result);
} else {
    $sql_search = "";

    $total_count = $board[bo_total_count];
    
    // $ca_code���� $board[bo_1]�� ���� �ִ� ��� by john2karl 2004-12-30 21:42:00
    // $ca_code���� �ִ� ��� by john2karl 2004-12-27 08:00:00
    if ($ca_code && $board[bo_1]) {
        $sql_ca_code2 = get_sql_category_code($write_table, $ca_code, "");
        $sql = " select distinct wr_parent_id from $write_table where $sql_ca_code2";
        $result = sql_query($sql);
        $total_count = mysql_num_rows($result);
    } else if($ca_code && !$board[bo_1]) {
        $sql = " select distinct wr_parent_id from $write_table where ca_code = '$ca_code'";
        $result = sql_query($sql);
        $total_count = mysql_num_rows($result);
    }
}

$total_page  = ceil($total_count / $board[bo_page_rows]);  // ��ü ������ ���
if (!$page) { $page = 1; } // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $board[bo_page_rows]; // ���� ���� ����

if (!$html_title) {
    $html_title = "$group[gr_subject] > $board[bo_subject] ��� : ������ $page";
}

// �����ڶ�� ���û��� ����
$is_checkdelete = false;
if ($member[mb_id] && ($is_admin == 'default' || $group[gr_admin] == $member[mb_id] || $board[bo_admin] == $member[mb_id])) {
    $is_checkdelete = true;
}

$admin_href = "";
// �ְ������ �Ǵ� �׷�����ڶ��
if ($member[mb_id] && ($is_admin == 'default' || $group[gr_admin] == $member[mb_id])) {
    $admin_href = "./?doc=bbs/admin/boardform.php&w=u&bo_table=$bo_table";
}

// ���Ŀ� ����ϴ� QUERY_STRING
$qstr2 = "bo_table=$bo_table&soperator=$soperator";

if ($board[bo_gallery_width]) {
    $td_width = (int)(100 / $board[bo_gallery_width]);
}

// ����
if (!$ssort) {
    $ssort  = "wr_notice, wr_num, wr_reply";
    $sorder = "";
}
$sql_order = " order by $ssort $sorder ";


// $sql���� $sql_ca_code �߰� by john2karl 2004-12-26 19:03:00
if ('' != $stext) {
    $sql = " select distinct wr_parent_id
               from $write_table
              where (1) $sql_search $sql_ca_code
              $sql_order
              limit $from_record, $board[bo_page_rows] ";
    $result = sql_query($sql);
} else {
    $sql = " select *
               from $write_table
              where wr_comment = '0' $sql_ca_code
              $sql_order
              limit $from_record, $board[bo_page_rows] ";
    $result = sql_query($sql);
}

unset($list);

$save_wr_num = $save_wr_notice = 0;
for ($i=0; $row=mysql_fetch_array($result); $i++) {
    $tr = "";
    if ($i && $i%$board[bo_gallery_width]==0) {
        $tr = "</tr><tr>";
    }

    if ('' != $stext) {
        $row = sql_fetch(" select * from $write_table where wr_id = '$row[wr_parent_id]' and wr_comment = '0' ");

        $subject = conv_subject($row[wr_subject], $board[bo_subject_len], "��");
    } else {
        $subject = cut_str($row[wr_subject], $board[bo_subject_len], "��");
        $subject = get_text($subject);
    }

    // �迭��ü�� ����
    $list[$i] = $row;
    
    $list[$i][subject] = $subject;
    // 3.25
    // ��Ͽ��� ���� �̸����� ����� �Խ��Ǹ� ������ ��ȯ�� (�ӵ� ���) : kkal3(Ŀ��)�Բ��� �˷��ּ̽��ϴ�.
    if ($board[bo_use_listcontent])
        $list[$i][content] = conv_content($row[wr_content], $row[wr_html]);

    $list[$i][is_notice] = false;
    if ($row[wr_notice] == -1) {
        $list[$i][num] = "����";
        $list[$i][is_notice] = true;
    } else if ($save_wr_num != $row[wr_num] || $save_wr_notice != $row[wr_notice]) {
        $list[$i][num] = $row[wr_num] * -1;
    } else {
        $list[$i][num] = "&nbsp;";
    }

    if ($wr_id == $row[wr_id]) {
        $list[$i][num] = "<font color=crimson><b>��</b></font>";
    }

    //$list[$i][serial] = $total_count - $i - $from_record;

    $list[$i][ca_name] = get_category_name($write_table, $row[ca_id]);
    
    $list[$i][commentcnt] = "";
    if ($row[wr_commentcnt]) {
        $list[$i][commentcnt] = "($row[wr_commentcnt])";
    }
    
    $list[$i][datetime] = substr($row[wr_datetime],2,8);

    // �ӵ��� ������ �ϱ� ���Ͽ� ����
    // ���� ����
    //$sql2 = " select mb_open from $cfg[table_member] where mb_id = '$row[mb_id]' ";
    //$mb = sql_fetch($sql2);

    $list[$i][name] = gblayer($row[mb_id], cut_str($row[wr_name],12,''), $row[wr_email], $row[wr_homepage]);

    $list[$i][reply] = "";
    if (strlen($row[wr_reply]) > 0) {
        for ($k=0; $k<strlen($row[wr_reply]); $k++) {
            $list[$i][reply] .= " &nbsp;&nbsp; ";
        }
    }

    $list[$i][icon_reply] = "";
    if ($list[$i][reply]) {
        $list[$i][icon_reply] = "<img src='$board_skin/icon_reply.gif' align='absmiddle'>";
    }

    $list[$i][icon_file] = "";
    if ($row[wr_file1] || $row[wr_file2]) {
        $list[$i][icon_file] = "<img src='$board_skin/icon_file.gif' align='absmiddle'>";
    }

    $list[$i][icon_link] = "";
    if ($row[wr_link1] || $row[wr_link2]) {
        $list[$i][icon_link] = "<img src='$board_skin/icon_link.gif' align='absmiddle'>";
    }

    $list[$i][href] = "./?doc=bbs/gnuboard.php&$qstr&page=$page&wr_id=$row[wr_id]";

    $list[$i][icon_new] = "";
    if ($row[wr_datetime] >= date("Y-m-d H:i:s", time() - $board[bo_new] * 3600)) {
        $list[$i][icon_new] = "<img src='$board_skin/icon_new.gif' align='absmiddle'>";
    }

    $list[$i][icon_hot] = "";
    if ($row[wr_hit] >= $board[bo_hot]) {
        $list[$i][icon_hot] = "<img src='$board_skin/icon_hot.gif' align='absmiddle'>";
    }

    $list[$i][icon_battle] = "";
    if ($row[wr_commentcnt] >= $board[bo_battle]) {
        $list[$i][icon_battle] = "<img src='$board_skin/icon_battle.gif' align='absmiddle'>";
    }

    $list[$i][icon_secret] = "";
    if ($row[wr_secret]) {
        $list[$i][icon_secret] = "<img src='$board_skin/icon_secret.gif' align='absmiddle'>";
    }

    for ($k=1; $k<=$cfg[file_count]; $k++) {
        if (@preg_match($cfg[image_extension], $row["wr_file".$k])) {
            $list[$i]["file_image".$k] = "./data/file/$bo_table/" . $row["wr_file".$k];
        }
    }

    for ($k=1; $k<=$cfg[link_count]; $k++) {
        if ($row["wr_link".$k]) {
            $link[$i]["link".$k] = set_http(get_text(cut_str($row["wr_link".$k], 255)));
            $link[$i]["link_href".$k] = "./?doc=bbs/gblink.php&$qstr&wr_id=$row[wr_id]&index=$k";
        }
    }
    
    $save_wr_num = $row[wr_num];
    $save_wr_notice = $row[wr_notice];
}

$write_pages = get_paging($default[de_write_pages], $page, $total_page, "./?doc=bbs/gnuboard.php&$qstr&page=");

// �˻� spart
$prev_spart_href = "";
if ($spart - $cfg[search_spart] >= $min_spart && isset($min_spart)) {
    $prev_spart = $spart - $cfg[search_spart];
    $prev_spart_href = "./?doc=bbs/gnuboard.php&bo_table=$bo_table&sselect=$sselect&stext=$stext&spart=$prev_spart";
}

$next_spart_href = "";
if ($spart + $cfg[search_spart] < 0) {
    $next_spart = $spart + $cfg[search_spart];
    $next_spart_href = "./?doc=bbs/gnuboard.php&bo_table=$bo_table&sselect=$sselect&stext=$stext&spart=$next_spart";
}

$list_href = "";
if ('' != $stext) {
    $list_href = "./?doc=bbs/gnuboard.php&bo_table=$bo_table";
}

$write_href = "";
if ($member[mb_level] >= $board[bo_write_level]) {
    $write_href = "./?doc=bbs/gbform.php&w=&bo_table=$bo_table";
}

include "$board_skin/gblist.skin.php";
?>
