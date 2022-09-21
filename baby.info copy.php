<?php
include('./utility/mysql.php');

$json_string = file_get_contents("./company.json");

// var_dump($json_string);

// $json_decode($json_string,true)


$cid = "3b897c6b-53c0-415d-9080-cd530b769da1";
// $cid = "e0afae7e-4968-47bd-8d77-f1ceb4067fdd";


// $method = strtolower($_SERVER['REQUEST_METHOD']);

// if ($method == 'post') {
//   $input = json_decode(file_get_contents('php://input'));

//   if (!isset($input->flow)) {
//     die('Operation Denied!');
//   }

//   $flow = $input->flow;

//   if ($flow == 'listBaby') {
//   }
// }


// $babys = [];
// $doctors = [];
// $members = [];


// // 根据cid 获得医院下面的医生列表
// $sql = "select * from doctor where cid='$cid'";
// $result =  $conn->query($sql);
// if ($conn->affected_rows != 0) {
//   while ($tmp = $result->fetch_assoc()) {
//     array_push($doctors, $tmp);
//   }
// }

// // 获得医生下面的监护人信息
// foreach ($doctors as $doctor) {
//   $did = $doctor['id'];
//   $sql = "select * from member where did='$did'";

//   $result =  $conn->query($sql);
//   if ($conn->affected_rows != 0) {
//     while ($tmp = $result->fetch_assoc()) {
//       array_push($members, $tmp);
//     }
//   }
// }

// // 根据监护人获得宝宝信息
// foreach ($members as $member) {
//   $mid = $member['id'];
//   $sql = "select name from baby where mid='$mid'";

//   $result =  $conn->query($sql);
//   if ($conn->affected_rows != 0) {
//     while ($tmp = $result->fetch_assoc()) {
//       array_push($babys, $tmp);
//     }
//   }
// }


// var_dump($babys);
