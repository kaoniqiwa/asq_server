<?php

include('./utility/tool.php');
include('./utility/mysql.php');



$method = strtolower($_SERVER['REQUEST_METHOD']);


if ($method == 'post') {

  $input = json_decode(file_get_Contents('php://input'));

  if (!isset($input->Flow)) {
    die('Operation Denied!');
  }

  $Flow = $input->Flow;

  if ($Flow == 'listInforms') {

    $sql = "SELECT  Id,Content,CreateTime,UpdateTime  FROM `inform`";
    $result = $conn->query($sql);
    $model = [];
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($model, $tmp);
      }
    }
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" =>  $model
      ]
    );
  } else if ($Flow == 'getLatestInform') {
    $sql = "select Id,Content,CreateTime,UpdateTime  FROM `inform` WHERE  IsLatest='是'";
    $result = $conn->query($sql);
    $model = null;
    if ($conn->affected_rows != 0) {
      $model = $result->fetch_assoc();
    }
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" =>  $model
      ]
    );
  } else if ($Flow == 'addInform') {

    $Id = GUID();
    $Content = $input->Content;

    $sql = "update inform set IsLatest ='否'";

    $conn->query($sql);

    $sql = "insert into inform (Id,Content,IsLatest) values ('$Id','$Content','是')";
    $conn->query($sql);
    $sql  = "select Id,Content,CreateTime,UpdatetIME  from inform where Id='$Id'";
    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }
  } else if ($Flow == 'deleteInform') {
    $Id = $input->Id;


    $sql = "delete from inform where Id='$Id'";

    $conn->query($sql);

    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
      ]
    );
  }
} else if ($method == 'get') {
}
