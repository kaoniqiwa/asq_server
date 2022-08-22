<?php

include('./util.php');


if (!isset($conn)) {
  $conn = new mysqli('localhost', 'root', 'root');
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset('utf8');
  $conn->select_db('asq');
}


$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {
  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->flow)) {
    die('Operation Denied!');
  }

  $flow = $input->flow;

  if ($flow == 'listDoctor') {
    $cid = $input->cid;
    $doctors = listDoctor($cid);
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $doctors
      ]
    );
  } else if ($flow == 'addDoctor') {
    $id = GUID();
    $cid = $input->cid;
    $name = $input->name;
    $level = $input->level;
    $dept = $input->dept;
    $phone =  $input->phone;
    $sql = "insert into doctor (id,cid,name,level,dept,phone) values ('$id','$cid','$name','$level','$dept','$phone')";

    $conn->query($sql);
    $result = $conn->query("select id,cid,name,level,dept,create_time,update_time from doctor where id='$id'");

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
    $id = $input->id;
    $cid = $input->cid;

    $sql = "delete from doctor where id='$id'";
    $result = $conn->query($sql);
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
      ]
    );
  } else if ($flow == 'editDoctor') {
    $id = $input->id;
    $name = $input->name;
    $level = $input->level;
    $dept = $input->dept;
    $phone =  $input->phone;
    $sql  = "update doctor set name='$name',level='$level',dept='$dept',phone='$phone' where id='$id'";
    $conn->query($sql);

    $result = $conn->query("select id,cid,name,level,dept,create_time,update_time from doctor where id='$id'");

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
  }
} else if ($method == 'get') {
  $id = $_GET['id'];
  $doctor = getDoctor($id);

  echo json_encode(
    [
      "faultCode" => 0,
      'faultReason' => 'OK',
      "data" =>  $doctor
    ]
  );
}
function getDoctor(string $id)
{
  global $conn;
  $sql =  "select id,cid,name,level,dept,create_time,update_time from doctor where id='$id'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    return $result->fetch_assoc();
  }
  return null;
}

function listDoctor(string $cid)
{
  global $conn;
  $data = [];
  $sql = "select id,cid,name,level,dept,create_time,update_time from doctor where cid='$cid' ";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($data, $tmp);
    }
  }
  return $data;
}
