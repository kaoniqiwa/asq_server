<?php

include('./tool.php');
include("./mysql.php");


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
  if ($flow == 'listCompany') {
    $pageSize = $input->pageSize;
    $pageIndex = $input->pageIndex;
    $name = '';
    if (isset($input->name)) {
      $name = $input->name;
    }
    $start = ($pageIndex - 1) * $pageSize;


    $sql = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company  where name like '%$name%'  or username like '%$name%' limit $start,$pageSize";

    $totalRecortCount = $conn->query("select count(*) from company  where name like '%$name%' or username like '%$name%'")->fetch_assoc()['count(*)'];


    $totalRecortCount = intval($totalRecortCount);
    $PageCount  = ceil($totalRecortCount / $pageSize);
    $RecordCount = 0;

    $result = $conn->query($sql);
    $Data = array();

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }


    foreach ($Data as &$value) {
      $id = $value['id'];
      $doctors = getDoctor($id);
      $value['doctors'] = $doctors;
    }



    echo json_encode(
      array(
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" => [
          "data" => $Data,
          "page" => array(
            "pageCount" => $PageCount,
            "pageSize" => $pageSize,
            "pageIndex" => $pageIndex,
            "recordCount" => $RecordCount,
            "totalRecordCount" => $totalRecortCount
          )
        ]
      )
    );
  } else if ($flow == 'addCompany') {
    $id = GUID();
    $name = $input->name;
    $username = $input->username;
    $password = $input->password;
    $asq_total = $input->asq_total;
    $asq_left = $input->asq_left;
    $asq_se_total = $input->asq_se_total;
    $asq_se_left = $input->asq_se_left;
    $asq_se_2_total = $input->asq_se_2_total;
    $asq_se_2_left = $input->asq_se_2_total;

    $sql = "insert into company ( id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left) values ('$id','$name','$username','$password','$asq_total','$asq_left','$asq_se_total','$asq_se_left','$asq_se_2_total','$asq_se_2_left')";

    $result = $conn->query($sql);

    $sql  = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company where id='$id'";
    $result =  $conn->query($sql);
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
  } else if ($flow == 'deleteCompany') {
    $id = $input->id;

    $sql = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company where id='$id'";


    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model = $result->fetch_assoc();

      $conn->query("delete from doctor where cid='$id'");
      $conn->query("delete from company where id='$id'");

      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          "data" => $model
        ]
      );
    } else {
      echo json_encode(
        [
          "faultCode" => 1,
          'faultReason' => 'Error',
        ]
      );
    }
  } else if ($flow == 'editCompany') {
    $id = $input->id;
    $name = $input->name;
    $username = $input->username;
    $password = $input->password;
    $asq_total = $input->asq_total;
    $asq_left = $input->asq_left;
    $asq_se_total = $input->asq_se_total;
    $asq_se_left = $input->asq_se_left;
    $asq_se_2_total = $input->asq_se_2_total;
    $asq_se_2_left = $input->asq_se_2_total;

    $sql  = "update  company set name='$name',username='$username',password='$password',asq_total='$asq_total',asq_left='$asq_left',asq_se_total='$asq_se_total',asq_se_left='$asq_se_left',asq_se_2_total='$asq_se_2_total',
    asq_se_2_left='$asq_se_2_left' where id='$id'";
    $conn->query($sql);


    $sql  = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company where id='$id'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();

      $doctors = getDoctor($id);
      $model['doctors'] = $doctors;

      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          'data' => $model
        ]
      );
    }
  } else if ($flow == 'exportCompany') {
    $beginTime = getTime($input->beginTime);
    $endTime = getTime($input->endTime);

    $sql  = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company where create_time between '$beginTime' and '$endTime'";

    $result = $conn->query($sql);
    $Data = [];

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }


    foreach ($Data as &$value) {
      $id = $value['id'];
      $doctors = getDoctor($id);
      $value['doctors'] = $doctors;
    }

    echo json_encode(
      array(
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" => $Data
      )
    );
  }
} else if ($method == 'get') {
  $id = $_GET['id'];
  $sql = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company where id='$id'";

  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $model  = $result->fetch_assoc();
    $doctors = getDoctor($id);
    $model['doctors'] = $doctors;

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $model
      ]
    );
  }
}


function getDoctor(string $cid)
{
  global $conn;
  $data = [];
  $sql = "select id,cid,name,level,dept,phone,create_time,update_time from doctor where cid='$cid' ";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($data, $tmp);
    }
  }
  return $data;
}
