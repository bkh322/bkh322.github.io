<?php
header('Content-Type: text/html; charset=UTF-8');
$db_host = "localhost"; 

$db_user = "gaehwa0427"; 

$db_passwd = "!bg920427";

$db_name = "gaehwa0427"; 
// MySQL - DB 접속.

$conn = mysqli_connect($db_host,$db_user,$db_passwd,$db_name);
/*
if (mysqli_connect_errno()){

echo "MySQL 연결 오류: " . mysqli_connect_error();

exit;

} else {

echo "DB : \"$db_name\"connect ok.<br/>";

}*/
?>
