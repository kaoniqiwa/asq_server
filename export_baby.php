<?php

include('./utility/mysql.php');
include('./export/PHPExcel.php');




// // $sql = "select id,mid,name,gender,birthday,survey_time,premature,is_shun,identity_info,identity_type,weight,is_help,is_multi,other_abnormal,create_time,update_time from baby where mid like '%$mid%'";



$sql = "select name,gender,birthday ,survey_time,premature,is_shun,identity_info,identity_type,weight from baby";


$babys = [];

$result = $conn->query($sql);
if ($conn->affected_rows != 0) {
  while ($tmp = $result->fetch_assoc()) {
    array_push($babys, $tmp);
  }

  foreach ($babys as &$baby) {
    $baby['gender'] = $baby['gender'] == '0' ? '男' : '女';
    $baby['premature'] = $baby['premature'] == '0' ? '是' : '否';
    $baby['is_shun'] = $baby['is_shun'] == '0' ? '是' : '否';
  }
}
var_dump($babys);

// exportExcel(['姓名', '性别', '生日', '筛查时间', '是否早产', '是否顺产',], $babys, "宝宝信息： " . date('Y-m-d'), "./", true);









// exportExcel(['a', 'b'], [
//   [
//     "name" => 'A2',
//     "age" => "B2",
//   ],
//   [
//     "name" => 'A3',
//     "age" => "B3",
//   ],
// ], '宝宝', "./", true);
