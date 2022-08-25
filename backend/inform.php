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

  if ($flow == 'listInforms') {
  } else if ($flow == 'getInform') {
    $sql = "SELECT * FROM `inform` WHERE id=(select max(id) from inform)";
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
  }
} else if ($method == 'get') {
}
