<?php
    include '../db/db.php';
?>
<html>
<head>
<title>gaehwa db TEST</title>
</head>
<body>
<?
$sql = "SELECT * FROM `board`";
if ($result = mysqli_query($conn,$sql)).{

echo "<table border='1' cellpadding='5'> <tr nowrap='' bgcolor='#e0e0e0'> 

<th>번호</th> 

<th>제목</th> 

<th>이름</th> 

<th>작성날짜</th>

</tr>";

while($row = mysqli_fetch_array($result)){

echo "<tr>";

echo "<td nowrap=''>" . $row['num'] . "</td>";

echo "<td nowrap='' bgcolor='#f4f0fa'>" . $row['title'] . "</td>";

echo "<td nowrap=''>" . $row['name'] . "</td>";

echo "<td nowrap='' bgcolor='#f4f0fa'>" . $row['day'] . "</td>";

echo "</tr>";

} 

echo "</table>";



mysqli_close($conn);



} else {

echo "테이블 쿼리 오류: " . mysqli_error($conn);

exit;

}
?>

</body>

</html>