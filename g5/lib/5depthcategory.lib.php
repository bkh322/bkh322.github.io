<?
if (defined(__FILE__)) return;
define(__FILE__, TRUE);

// 개발자(사용자) 라이브러리

// 5단계분류를 사용하나?
function is_5depthcategory($table)
{
    global $cfg;

		$table = $table . "_cat";
	
    $is = false;
    $result = mysql_list_tables($cfg[mysql_db]);
    for ($i=0; $i<mysql_num_rows($result); $i++) {
        if ($table == mysql_tablename($result, $i)) {
            $resultfield = mysql_list_fields($cfg[mysql_db], $table);
            // 테이블의 두번째 필드가 ca_code 이면 게시판 테이블이다.
            if (mysql_field_name($resultfield, 1) == "ca_code") {
                $is = true;
                break;
            }
        }
    }

    return $is;
}


// 현재 분류의 위치를 출력해준다.
function get_cat_pos_str($table, $category_code, $url) 
{
		global $cfg;
		
		// 입력 받은 $category_code값의 자리수가 9이면 앞에 0을 붙인다.
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
    // 위치 출력하고 싶으면 아래 //을 삭제
		return $cat_pos_str;
}


// value를 $ca_code값으로 함 by john2karl 2004-12-30 12:09:00
// 분류 옵션을 얻음
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


// 분류 옵션을 얻음
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


// 입력된 분류의 두자리 수 분류의 값을 구한다. (1부터 99)
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

// 두자리 분류의 두자리 수 분류의 값을 다시 전체 분류값으로 변환한다.
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
// 같은 분류에서 1부터 99중 비어있는 코드를 얻는다. (2004-10-21 수정해야함)
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


// 같은 분류에서 사용 가능한 코드를 얻는다.
function get_next_code($table, $category_parent_code, $category_depth) 
{
		global $cfg;
		
		// 같은 분류의 가장 큰 코드값을 불러옴
		$sql_cat1 = " select max(ca_code) from {$table}_cat
										where ca_parent_code = '$category_parent_code' 
							 ";
		$row_cat1 = sql_fetch($sql_cat1);
				
		// 두자리 분류값을 얻음
		$category_now_code = get_now_code($table, $category_parent_code, $row_cat1[0], $category_depth);
		
		if ( $category_now_code == 99 ) {
		
				// 두 자리 분류값이 99를 넘었을 경우 1부터 99까지 카테고리가 없는 값을 사용한다.
				$next_category_code = get_usable_code($table, $category_parent_code, $category_depth);
				
				if ( !$next_category_code) {
						alert("비어있는 분류값이 없습니다.");
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

// 분류의 깊이에 따른 공백값을 구한다.
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


// 트리메뉴를 구한다
function get_category_treemenu2($skin_dir, $bo_table, $category_code, $url) 
{ 
		global $cfg;

    // 스킨디렉토리값이 넘어왔다면
    if ($skin_dir) {
        $board_skin = "./bbs/skin/board/$skin_dir";
    }
    
		$sql1 = " select * from {$cfg[write_table_prefix]}{$bo_table}_cat order by ca_code asc ";
		$result1 = sql_query($sql1);
		
		// 카운트
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
        
        // 1단계 마지막 분류값이면
        if ($row1[ca_code] == $max_1depth_ca_code) {
            $category_img_str = "<img src='$board_skin/icon_nosub.gif' border=0>";
        }
        if ($row1[is_rowcat] ) $category_img_str = "<img src='$board_skin/icon_plus.gif' border=0 id='img$row1[ca_code]'>"; 

        $category_location_str = "./?doc=bbs/gnuboard.php&bo_table=$bo_table&wr_id=$row1[wr_id]&ca_code=$row1[ca_code]";
        // 링크 주소값이 있다면
        if ($url) {
            $category_location_str = $url;
        }
				$category_onclick_str = "";
				
				// 하위 분류값이 있다면
				if ($row1[is_rowcat]) {
						$category_onclick_str = "onclick=\"displaySubRow('code$row1[ca_code]', 'img$row1[ca_code]');\"";
				}
				$category_row_str = "<a $category_onclick_str>$category_depth_str$category_img_str</a><a href='$category_location_str'>$row1[ca_name]</a>\n";
				if ( $before_category_depth == $row1[ca_depth] ) { // 같은 깊이의 분류

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
		
		// $category_code 값이 있으면 그 코드값까지 트리메뉴를 연다.
		
		
		// 입력 받은 $category_code값의 자리수가 9이면 앞에 0을 붙인다.
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

// $and을 $login_operator로 바꿈 by john2karl 2004-12-26 19:09:00 
// ca_code의 값으로 하위 분류까지 검색할 수 있는 sql문을 얻어온다. 
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
