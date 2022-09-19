<?php

include('./utility/mysql.php');
include('./export/PHPExcel.php');




// // $sql = "select id,mid,name,gender,birthday,survey_time,premature,is_shun,identity_info,identity_type,weight,is_help,is_multi,other_abnormal,create_time,update_time from baby where mid like '%$mid%'";



// $sql = "select name,gender,birthday from baby";


// $babys = [];

// $result = $conn->query($sql);
// if ($conn->affected_rows != 0) {
//   while ($tmp = $result->fetch_assoc()) {
//     array_push($babys, $tmp);
//   }

// var_dump($babys);
// }





exportExcel(['a', 'b'], [[
  "name" => 'sdf',
  "age" => 12
]], '宝宝', "./", true);
