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

  if ($Flow == 'listDoctor') {
    $Cid = $input->Cid;
    $data = [];

    $sql = "select Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime from doctor where Cid like '%$Cid%'";

    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($data, $tmp);
      }
    }

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $data
      ]
    );
  } else if ($Flow == 'addDoctor') {
    $Id = GUId();
    $Cid = $input->Cid;
    $Name = $input->Name;
    $Level = $input->Level;
    $Dept = $input->Dept;
    $Phone =  $input->Phone;
    $sql = "insert into doctor (Id,Cid,Name,Level,Dept,Phone) values ('$Id','$Cid','$Name','$Level','$Dept','$Phone')";

    $conn->query($sql);
    $result = $conn->query("select Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime from doctor where Id='$Id'");

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
  } else if ($Flow == 'deleteDoctor') {
    $Id = $input->Id;
    $Cid = $input->Cid;

    $sql = "delete from doctor where Id='$Id'";
    $result = $conn->query($sql);
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
      ]
    );
  } else if ($Flow == 'editDoctor') {
    $Id = $input->Id;
    $Name = $input->Name;
    $Level = $input->Level;
    $Dept = $input->Dept;
    $Phone =  $input->Phone;
    $sql  = "update doctor set Name='$Name',Level='$Level',Dept='$Dept',Phone='$Phone' where Id='$Id'";
    $conn->query($sql);

    $result = $conn->query("select Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime from doctor where Id='$Id'");

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
  $Id = $_GET['Id'];
  $doctor  = null;

  $sql =  "select Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime from doctor where Id='$Id'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $doctor =  $result->fetch_assoc();
  }

  echo json_encode(
    [
      "faultCode" => 0,
      'faultReason' => 'OK',
      "data" =>  $doctor
    ]
  );
}
