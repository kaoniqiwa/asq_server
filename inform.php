<?php

include('./tool.php');


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

  if ($flow == 'listInforms') {

    $sql = "SELECT  id,content,create_time,update_time  FROM `inform`";
    $result = $conn->query($sql);
    $model = [];
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($model, $tmp);
      }
    }
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $model
      ]
    );
  } else if ($flow == 'getLatestInform') {
    $sql = "SELECT id,content,create_time,update_time  FROM `inform` WHERE  is_latest='1'";
    $result = $conn->query($sql);
    $model = null;
    if ($conn->affected_rows != 0) {
      $model = $result->fetch_assoc();
    }
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $model
      ]
    );
  } else if ($flow == 'addInform') {

    $id = GUID();
    $content = $input->content;

    $sql = "update inform set is_latest ='0'";

    $conn->query($sql);

    $sql = "insert into inform (id,content) values ('$id','$content')";
    $conn->query($sql);
    $sql  = "select id,content,create_time,update_time  from inform where id='$id'";
    $result = $conn->query($sql);

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
  } else if ($flow == 'deleteInform') {
    $id = $input->id;


    $sql = "update inform set is_latest ='0'";

    $conn->query($sql);

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
      ]
    );
  }
} else if ($method == 'get') {
}
