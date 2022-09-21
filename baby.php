<?php
include('./utility/tool.php');
include('./utility/mysql.php');


// if (!isset($conn)) {
//   $conn = new mysqli('localhost', 'root', 'root');
//   if ($conn->connect_errno) {
//     die('database connect fail');
//   }

//   $conn->set_charset('utf8');
//   $conn->select_db('asq');
// }


$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {

  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->flow)) {
    die('Operation Denied!');
  }

  $flow = $input->flow;

  if ($flow == 'listBaby') {
    $mid = isset($input->mid) ? $input->mid : "";

    $sql = "select id,mid,name,gender,birthday,survey_time,premature,is_shun,identity_info,identity_type,weight,is_help,is_multi,other_abnormal,create_time,update_time from baby where mid like '%$mid%'";

    $babys = [];

    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($babys, $tmp);
      }
    }

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $babys
      ]
    );
  } else if ($flow == 'addBaby') {
    $id = GUID();
    $mid = $input->mid;
    $name = $input->name;
    $gender = $input->gender;
    $birthday = getTime($input->birthday);
    $survey_time =  getTime($input->survey_time);
    $premature =  (int)$input->premature;
    $is_shun =  (int)$input->is_shun;

    $identity_info =  $input->identity_info;
    $identity_type =  $input->identity_type;
    $weight =  $input->weight;
    $is_help =  (int)$input->is_help;
    $is_multi =  (int)$input->is_multi;
    $other_abnormal =  $input->other_abnormal;


    $sql = "insert into baby (id,mid,name,gender,birthday,survey_time,premature,is_shun,identity_info,identity_type,weight,is_help,is_multi,other_abnormal) values ('$id','$mid','$name','$gender','$birthday','$survey_time','$premature','$is_shun','$identity_info','$identity_type','$weight','$is_help','$is_multi','$other_abnormal')";

    $conn->query($sql);
    $result = $conn->query("select id,mid,name,gender,birthday,survey_time,premature,is_shun,identity_info,identity_type,weight,is_help,is_multi,other_abnormal,create_time,update_time from baby where id='$id'");

    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          'data' => $model
        ]
      );
    }
  } else if ($flow == 'deleteDoctor') {
    // $id = $input->id;
    // $cid = $input->cid;

    // $sql = "delete from doctor where id='$id'";
    // $result = $conn->query($sql);
    // echo json_encode(
    //   [
    //     "faultCode" => 0,
    //     'faultReason' => 'OK',
    //   ]
    // );
  } else if ($flow == 'editDoctor') {
    // $id = $input->id;
    // $name = $input->name;
    // $level = $input->level;
    // $dept = $input->dept;
    // $phone =  $input->phone;
    // $sql  = "update doctor set name='$name',level='$level',dept='$dept',phone='$phone' where id='$id'";
    // $conn->query($sql);

    // $result = $conn->query("select id,cid,name,level,dept,create_time,update_time from doctor where id='$id'");

    // if ($conn->affected_rows != 0) {
    //   $model  =  $result->fetch_assoc();
    //   echo json_encode(
    //     [
    //       "faultCode" => 0,
    //       'faultReason' => 'OK',
    //       'data' => $model
    //     ]
    //   );
    // }
  }
} else if ($method == 'get') {
  $id = $_GET['id'];
  $baby = null;

  $sql =  "select id,mid,name,gender,birthday,survey_time,premature,is_shun,identity_info,identity_type,weight,is_help,is_multi,other_abnormal,create_time,update_time from baby where id='$id'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $baby =  $result->fetch_assoc();
  }
  echo json_encode(
    [
      "faultCode" => 0,
      'faultReason' => 'OK',
      "data" =>  $baby
    ]
  );
}
