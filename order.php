<?php

include('./utility/tool.php');
include('./utility/mysql.php');


$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {
  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->Flow)) {
    die('Operation Denied!');
  }

  $Flow = $input->Flow;

  if ($Flow == 'listOrder') {

    $PageSize = $input->PageSize;
    $PageIndex = $input->PageIndex;
    $Phone = '';
    if (isset($input->Phone)) {
      $Phone = $input->Phone;
    }
    $start = ($PageIndex - 1) * $PageSize;


    $sql = "select Id,Name,Phone,OrderType,Price ,CreateTime from orders  where Phone  like '%$Phone%' limit $start,$PageSize";

    $TotalRecortCount = $conn->query("select count(*) from orders  where Phone  like '%$Phone%'")->fetch_assoc()['count(*)'];


    $TotalRecortCount = intval($TotalRecortCount);
    $PageCount  = ceil($TotalRecortCount / $PageSize);
    $RecordCount = 0;

    $result = $conn->query($sql);
    $Data = array();

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }




    echo json_encode(
      array(
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" => [
          "Data" => $Data,
          "Page" => array(
            "PageCount" => $PageCount,
            "PageSize" => $PageSize,
            "PageIndex" => $PageIndex,
            "RecordCount" => $RecordCount,
            "TotalRecordCount" => $TotalRecortCount
          )
        ]
      )
    );
  } else if ($Flow == 'addOrder') {
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());
  } else if ($Flow == 'deleteOrder') {
    $Id = $input->Id;

    $sql  = "select Id,Name,Phone,OrderType,Price ,CreateTime from orders where Id='$Id' ";
    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model = $result->fetch_assoc();

      $sql = "delete from orders where Id='$Id'";
      $result = $conn->query($sql);
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          "Data" => $model
        ]
      );
    } else {
      echo json_encode(
        [
          "FaultCode" => 1,
          'FaultReason' => 'Error',
        ]
      );
    }
  } else if ($Flow == 'editOrder') {
  } else if ($Flow == 'exportOrder') {
    $BeginTime = getTime($input->BeginTime);
    $EndTime = getTime($input->EndTime);

    $sql  = "select Id,Name,Phone,OrderType,Price ,CreateTime from orders where CreateTime between '$BeginTime' and '$EndTime'";

    $result = $conn->query($sql);
    $Data = [];

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }



    echo json_encode(
      array(
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" => $Data
      )
    );
  }
} else if ($method == 'get') {
}
