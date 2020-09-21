<?
if (defined(__FILE__)) return;
define(__FILE__, TRUE);

// ������(�����) ���̺귯��

// 5�ܰ�з��� ����ϳ�?
function is_5depthcategory($table)
{
    global $cfg;

		$table = $table . "_cat";
	
    $is = false;
    $result = mysql_list_tables($cfg[mysql_db]);
    for ($i=0; $i<mysql_num_rows($result); $i++) {
        if ($table == mysql_tablename($result, $i)) {
            $resultfield = mysql_list_fields($cfg[mysql_db], $table);
            // ���̺��� �ι�° �ʵ尡 ca_code �̸� �Խ��� ���̺��̴�.
            if (mysql_field_name($resultfield, 1) == "ca_code") {
                $is = true;
                break;
            }
        }
    }

    return $is;
}


// ���� �з��� ��ġ�� ������ش�.
function get_cat_pos_str($table, $category_code, $url) 
{
		global $cfg;
		
		// �Է� ���� $category_code���� �ڸ����� 9�̸� �տ� 0�� ���δ�.
		$category_code = (string)$category_code;
		$category_strlen = strlen($category_code);
		if ($category_strlen == 9) { $category_code = "0" .$category_code; } 
		
		$ca_1depth_code = substr($category_code, 0, 2) . "00000000";
		$ca_2depth_code = substr($category_code, 0, 4) . "000000";
		$ca_3depth_code = substr($category_code, 0, 6) . "0000";
		$ca_4depth_code = substr($category_code, 0, 8) . "00";
		$ca_5depth_code = substr($category_code, 0, 10);
		
		if ($category_strlen == 9) { $ca_1depth_code = substr($ca_1depth_code, 1, 9); }
		 
		$sql = "select * from {$table}_cat
                where ca_code = '$ca_1depth_code'
                  or ca_code = '$ca_2depth_code'
                  or ca_code = '$ca_3depth_code'
                  or ca_code = '$ca_4depth_code'
                  or ca_code = '$ca_5depth_code'
                  
                order by ca_depth asc
           ";
    
    $result = sql_query($sql);
    
    for($i=0; $row=mysql_fetch_array($result); $i++) {
        if($i != 0 ) {
        $cat_pos_str .= " > "; 
        }
        $cat_pos_str .= "<a href='$url&ca_code=$row[ca_code]'>$row[ca_name]</a>";
        //$key = $row[ca_depth] . "depth_ca_code";
        //$cat[$key] = $row[ca_code];
    } 
    
    // $temp = in_array("102020101", $cat);
    // echo $temp;
    // ��ġ ����ϰ� ������ �Ʒ� //�� ����
		return $cat_pos_str;
}


// value�� $ca_code������ �� by john2karl 2004-12-30 12:09:00
// �з� �ɼ��� ����
function get_category_option3($table)
{
    global $cfg;

    $sql = " select * from {$table}_cat order by ca_code ";
    $result = sql_query($sql);
    $str = "";
    while ($row = mysql_fetch_array($result)) {
    		$depth_str = get_depth_str ($row[ca_depth]);
        $str .= "<option value='$row[ca_code]'>$depth_str $row[ca_name]</option>\n";
    }
    mysql_free_result($result);

    return $str;
}


// �з� �ɼ��� ����
function get_category_option2($table)
{
    global $cfg;

    $sql = " select * from {$table}_cat order by ca_code ";
    $result = sql_query($sql);
    $str = "";
    while ($row = mysql_fetch_array($result)) {
    		$depth_str = get_depth_str ($row[ca_depth]);
        $str .= "<option value='$row[ca_id]'>$depth_str $row[ca_name]</option>\n";
    }
    mysql_free_result($result);

    return $str;
}


// �Էµ� �з��� ���ڸ� �� �з��� ���� ���Ѵ�. (1���� 99)
function get_now_code($category_parent_code, $category_code, $category_depth)
{
		global $cfg;

		switch ($category_depth) {
    		case "1" :
        		$category_now_code 	= $category_code / 100000000; 
        		break; 
        case "2" :
            $category_now_code  = ( $category_code - $category_parent_code ) / 1000000;
            break;
        case "3" :
        		$category_now_code 	= ( $category_code - $category_parent_code ) / 10000; 
        		break;
        case "4" :
        		$category_now_code	= ( $category_code - $category_parent_code ) / 100;
        		break;
        case "5" :
        		$category_now_code 	= $category_code - $category_parent_code;
        		break;
        default : 
            break;
    }
    
    return $category_now_code;
}

// ���ڸ� �з��� ���ڸ� �� �з��� ���� �ٽ� ��ü �з������� ��ȯ�Ѵ�.
function get_full_code($category_parent_code, $category_now_code, $category_depth)
{
		global $cfg;

		switch ($category_depth) {
    		case "1" :
        		$category_full_code 	= $category_now_code * 100000000; 
        		break; 
        case "2" :
            $category_full_code  = ( $category_now_code * 1000000 )+ $category_parent_code;
            break;
        case "3" :
        		$category_full_code 	= ( $category_now_code * 10000 ) + $category_parent_code; 
        		break;
        case "4" :
        		$category_full_code	= ( $category_now_code * 100 ) + $category_parent_code;
        		break;
        case "5" :
        		$category_full_code 	=  $category_now_code + $category_parent_code;
        		break;
        default : 
            break;
    }
    
    return $category_full_code;
}
// ���� �з����� 1���� 99�� ����ִ� �ڵ带 ��´�. (2004-10-21 �����ؾ���)
function get_usable_code($table, $category_parent_code, $category_depth)
{

		global $cfg;

		$sql_cat2 = " select * from {$table}_cat
										where ca_parent_code = '$category_parent_code' 
							 			order by ca_code asc
							 	";				
		$result_cat2 = sql_query($sql_cat2);
		
		$before_category_now_code = 0;
		
		for($c=0; $row_cat2=mysql_fetch_array($result_cat2); $c++) {
						
				$category_now_code = get_now_code($category_parent_code, $row_cat2[ca_code], $category_depth);
				
				if ( ( $before_category_now_code + 1 ) != $category_now_code ) {
				
						$next_category_code = get_full_code($category_parent_code, $before_category_now_code + 1, $category_depth);
						break;	
						
				} else {
				
						$before_category_now_code = $category_now_code;
				
				}
						
		}
							
		return $next_category_code;	
}


// ���� �з����� ��� ������ �ڵ带 ��´�.
function get_next_code($table, $category_parent_code, $category_depth) 
{
		global $cfg;
		
		// ���� �з��� ���� ū �ڵ尪�� �ҷ���
		$sql_cat1 = " select max(ca_code) from {$table}_cat
										where ca_parent_code = '$category_parent_code' 
							 ";
		$row_cat1 = sql_fetch($sql_cat1);
				
		// ���ڸ� �з����� ����
		$category_now_code = get_now_code($table, $category_parent_code, $row_cat1[0], $category_depth);
		
		if ( $category_now_code == 99 ) {
		
				// �� �ڸ� �з����� 99�� �Ѿ��� ��� 1���� 99���� ī�װ��� ���� ���� ����Ѵ�.
				$next_category_code = get_usable_code($table, $category_parent_code, $category_depth);
				
				if ( !$next_category_code) {
						alert("����ִ� �з����� �����ϴ�.");
				}
				
		} else {
				
				if ( !$row_cat1[0] ) {
				
						$row_cat1[0] = $category_parent_code;
						
				}

				switch ($category_depth) {
    				case "1" :
    						$next_category_code = $row_cat1[0] + 100000000;
        				break; 
        		case "2" :
            		$next_category_code = $row_cat1[0] + 1000000;
            		break;
        		case "3" :
        				$next_category_code = $row_cat1[0] + 10000;
        				break;
        		case "4" :
        				$next_category_code = $row_cat1[0] + 100;
        				break;
       	 		case "5" :
        				$next_category_code = $row_cat1[0] + 1;
        				break;
        		default : 
            		break;
    		}				
		
		}
		
		return $next_category_code;
}

// �з��� ���̿� ���� ���鰪�� ���Ѵ�.
function get_depth_str ($category_depth) {

		global $cfg;
		
		switch ($category_depth) {
				case "1" :
						$category_depth_str = "";
        		break; 
        case "2" :
						$category_depth_str = "&nbsp;&nbsp;";        
            break;
        case "3" :
						$category_depth_str = "&nbsp;&nbsp;&nbsp;&nbsp;";        
        		break;
        case "4" :
						$category_depth_str = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";        
        		break;
       	 case "5" :
						$category_depth_str = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";       	 
        		break;
        default : 
            break;
    }
    
    return $category_depth_str;			
		
}


// Ʈ���޴��� ���Ѵ�
function get_category_treemenu2($skin_dir, $bo_table, $category_code, $url) 
{ 
		global $cfg;

    // ��Ų���丮���� �Ѿ�Դٸ�
    if ($skin_dir) {
        $board_skin = "./bbs/skin/board/$skin_dir";
    }
    
		$sql1 = " select * from {$cfg[write_table_prefix]}{$bo_table}_cat order by ca_code asc ";
		$result1 = sql_query($sql1);
		
		// ī��Ʈ
		$sql2 = " select max(ca_code) from {$cfg[write_table_prefix]}{$bo_table}_cat where ca_depth = '1'";
		$result2 = sql_fetch($sql2);
    $max_1depth_ca_code = $result2[0];
    
		$category_treemenu_str .= "<SCRIPT language='Javascript'>\n";
		$category_treemenu_str .= "<!--\n";
		$category_treemenu_str .= "function displaySubRow(box, imgname) {\n";
    $category_treemenu_str .= "		var img = document.getElementById(imgname) ; \n";
		$category_treemenu_str .= "		var row = document.getElementById(box); \n";
    $category_treemenu_str .= "		if (row.style.display=='none')    { \n";
    $category_treemenu_str .= "   		row.style.display = ''; \n";
    $category_treemenu_str .= "    		img.src = '$board_skin/icon_minus.gif' \n";
    $category_treemenu_str .= "		}else{ \n";
    $category_treemenu_str .= "    		row.style.display = 'none'; \n";
    $category_treemenu_str .= "    		img.src = '$board_skin/icon_plus.gif' \n";
    $category_treemenu_str .= "		} \n";
		$category_treemenu_str .= "} \n";
		$category_treemenu_str .= "//-->\n";
		$category_treemenu_str .= "</script>\n";
		$category_treemenu_str .= "<table border=0 cellpadding=0 cellspacing=0 valgin=top>\n";
		$category_treemenu_str .= "<tr><td><a href='./?doc=bbs/gnuboard.php&bo_table=$bo_table'>$board[bo_subject]</a></td></tr>\n";
		$before_category_depth = 1;
		$before_category_parent_code = 0;
		$before_category_code = "";
		$category_depth_str = "";
		
		for ($i=0; $row1=mysql_fetch_array($result1); $i++) {
		
				$category_depth_str = get_depth_str($row1[ca_depth]);
        $category_img_str = "<img src='$board_skin/icon_nosub.gif' border=0>";
        
        // 1�ܰ� ������ �з����̸�
        if ($row1[ca_code] == $max_1depth_ca_code) {
            $category_img_str = "<img src='$board_skin/icon_nosub.gif' border=0>";
        }
        if ($row1[is_rowcat] ) $category_img_str = "<img src='$board_skin/icon_plus.gif' border=0 id='img$row1[ca_code]'>"; 

        $category_location_str = "./?doc=bbs/gnuboard.php&bo_table=$bo_table&wr_id=$row1[wr_id]&ca_code=$row1[ca_code]";
        // ��ũ �ּҰ��� �ִٸ�
        if ($url) {
            $category_location_str = $url;
        }
				$category_onclick_str = "";
				
				// ���� �з����� �ִٸ�
				if ($row1[is_rowcat]) {
						$category_onclick_str = "onclick=\"displaySubRow('code$row1[ca_code]', 'img$row1[ca_code]');\"";
				}
				$category_row_str = "<a $category_onclick_str>$category_depth_str$category_img_str</a><a href='$category_location_str'>$row1[ca_name]</a>\n";
				if ( $before_category_depth == $row1[ca_depth] ) { // ���� ������ �з�

						$category_treemenu_str .= "<!-- 1 -->\n";				
						$category_treemenu_str .= "<tr>\n";
						$category_treemenu_str .= "<td>\n";
						$category_treemenu_str .= $category_row_str;
						$category_treemenu_str .= "</td>\n";
						$category_treemenu_str .= "</tr>\n"; 		
				
				} else if ( ( $before_category_depth < $row1[ca_depth] ) and ( $before_category_code == $row1[ca_parent_code] ) ) {
						
						$category_treemenu_str .= "<!-- 2 -->\n";							
						$category_treemenu_str .= "<tr id='code$before_category_code' style='display:none;'>\n";
						$category_treemenu_str .= "<td>\n\n";
						$category_treemenu_str .= "<table cellpadding=0 cellspacing=0 border=0 valgin=top>\n";
						$category_treemenu_str .= "<tr>\n";
						$category_treemenu_str .= "<td>\n";
						$category_treemenu_str .= $category_row_str;
						$category_treemenu_str .= "</td>\n";
						$category_treemenu_str .= "</tr>\n";
						
				} else if ( ( $before_category_depth > $row1[ca_depth] ) and ( $before_category_code != $row1[ca_parent_code] ) ) {
					
						$category_treemenu_str .= "<!-- 3 -->\n";	    								
						$category_treemenu_str .= "</table>\n\n";
						
						$table_end_str = $before_category_depth - $row1[ca_depth];
						switch ($table_end_str) {
        	  case "2" :
								$category_treemenu_str .= "</td></tr></table>";        
            		break;
        		case "3" :
								$category_treemenu_str .= "</td></tr></table></td></tr></table>";        
        				break;
        		case "4" :
								$category_treemenu_str .= "</td></tr></table></td></tr></table></td></tr></table>";        
        				break;
        		default : 
            		break;
    				}								
		
						$category_treemenu_str .= "</td>\n";
						$category_treemenu_str .= "</tr>\n";
						$category_treemenu_str .= "<tr>\n";
						$category_treemenu_str .= "<td>\n";
						// $category_treemenu_str .= "<a onclick='dis(code$row1[ca_code]);'>$category_img_str</a><a href='$category_location_str'>$category_depth_str$row1[ca_name]</a>\n";
						$category_treemenu_str .= $category_row_str;
						$category_treemenu_str .= "</td>\n";
						$category_treemenu_str .= "</tr>\n";	
				
				} else {
				
						$category_treemenu_str .= "<!-- 4 -->\n";
						$category_treemenu_str .= "<tr>\n";
						$category_treemenu_str .= "<td>\n";
						// $category_treemenu_str .= "<a onclick='dis(code$row1[ca_code]);'>$category_img_str</a><a href='$category_location_str'>$category_depth_str$row1[ca_name]</a>\n";
						$category_treemenu_str .= $category_row_str;
						$category_treemenu_str .= "</td>\n";
						$category_treemenu_str .= "</tr>\n"; 
				
				}
					
				$before_category_depth = $row1[ca_depth];
				$before_category_parent_code = $row1[ca_parent_code];
				$before_category_code = $row1[ca_code];
		}
	  
	  switch ($before_category_depth) {
        case "1" :
            $category_treemenu_str .= "";
            break;           
        case "2" :
			      $category_treemenu_str .= "</table></td></tr>";        
            break;
        case "3" :
			      $category_treemenu_str .= "</table></td></tr></table></td></tr>";        
        		break;
        case "4" :
				    $category_treemenu_str .= "</table></td></tr><table></td></tr></table></td></tr>";        
        		break;
        case "5" :
            $category_treemenu_str .= "</table></td></tr><table></td></tr></table></td></tr></table></td></tr>"; 
            break;
        default : 
            break;		
		}
		
		$category_treemenu_str .= "</table>";
		
		// $category_code ���� ������ �� �ڵ尪���� Ʈ���޴��� ����.
		
		
		// �Է� ���� $category_code���� �ڸ����� 9�̸� �տ� 0�� ���δ�.
		$category_code = (string)$category_code;
		$category_strlen = strlen($category_code);
		if ($category_strlen == 9) { $category_code = "0" .$category_code; } 
		
		$ca_1depth_code = substr($category_code, 0, 2) . "00000000";
		$ca_2depth_code = substr($category_code, 0, 4) . "000000";
		$ca_3depth_code = substr($category_code, 0, 6) . "0000";
		$ca_4depth_code = substr($category_code, 0, 8) . "00";
		$ca_5depth_code = substr($category_code, 0, 10);
		
		if ($category_strlen == 9) { $ca_1depth_code = substr($ca_1depth_code, 1, 9); }
		 
		$sql = "select * from {$cfg[write_table_prefix]}{$bo_table}_cat
                where ca_code = '$ca_1depth_code'
                  or ca_code = '$ca_2depth_code'
                  or ca_code = '$ca_3depth_code'
                  or ca_code = '$ca_4depth_code'
                  or ca_code = '$ca_5depth_code'
                  
                order by ca_depth asc
           ";
    
    $result = sql_query($sql);
    
		$category_treemenu_str .= "<SCRIPT>";
		    
    for($i=0; $row=mysql_fetch_array($result); $i++) {
        $category_treemenu_str .=" displaySubRow(\"code$row[ca_code]\", \"img$row[ca_code]\");";
    } 
		$category_treemenu_str .= "</SCRIPT>";
		return $category_treemenu_str;
}

// $and�� $login_operator�� �ٲ� by john2karl 2004-12-26 19:09:00 
// ca_code�� ������ ���� �з����� �˻��� �� �ִ� sql���� ���´�. 
function get_sql_category_code($table, $category_code, $logic_operator) 
{ 
		global $cfg;
		
		$sql_cat1 = " select ca_depth from {$table}_cat where ca_code = '$category_code'";
		$row_cat1 = sql_fetch($sql_cat1);
		
		if ( $row_cat1[0] ) {
		
		    switch( $row_cat1[0] ) {
		        case "1":
		            $category_code2 = $category_code / 100000000;
		            $category_code2 = $category_code2 . "________";
		            break;
		        case "2":
		            $category_code2 = $category_code / 1000000;
		            $category_code2 = $category_code2 . "______";
		            break;
		        case "3":
		            $category_code2 = $category_code / 10000;
		            $category_code2 = $category_code2 . "____";
		            break;
		        case "4":
		            $category_code2 = $category_code / 100;
		            $category_code2 = $category_code2 . "__";
		            break;
		        case "5":
		            $category_code2 = $category_code;
		            break;
		        default:
		            break;
		    }
		    if ( $logic_operator == 1 ) $logic_operator_str = 'and';
        $sql_ca_code = $logic_operator_str . " ca_code like '".$category_code2."'";
		}
    return $sql_ca_code;
}
?>
