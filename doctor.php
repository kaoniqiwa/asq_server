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
    $PageSize = $input->PageSize;
    $PageIndex = $input->PageIndex;
    $Name = isset($input->Name) ? $input->Name : "";
    $Cids = isset($input->Cids) ? $input->Cids : [];
    $Ids = isset($input->Ids) ? $input->Ids : [];

    $sql = "select Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime from doctor  where Name like '%$Name%'";

    $Start = ($PageIndex - 1) * $PageSize;
    $result = $conn->query($sql);
    $tmp = [];

    if ($conn->affected_rows != 0) {
      while ($rs = $result->fetch_assoc()) {
        array_push($tmp, $rs);
      }
    }


    if (count($Cids) == 0) {
      $tmp2 = $tmp;
    } else {
      $tmp2 = [];
      for ($i = 0; $i < count($tmp); $i++) {
        for ($j = 0; $j < count($Cids); $j++) {
          if ($tmp[$i]['Cid'] == $Cids[$j]) {
            array_push($tmp2, $tmp[$i]);
            break;
          }
        }
      }
    }

    if (count($Ids) == 0) {
      $tmp3 = $tmp2;
    } else {
      $tmp3 = [];
      for ($i = 0; $i < count($tmp2); $i++) {
        for ($j = 0; $j < count($Ids); $j++) {
          if ($tmp[$i]['Id'] == $Ids[$j]) {
            array_push($tmp3, $tmp2[$i]);
            break;
          }
        }
      }
    }
    $TotalRecortCount = count($tmp3);
    $Data =  array_slice($tmp3, $Start, $PageSize);
    $PageCount  = ceil($TotalRecortCount / $PageSize);
    $RecordCount = count($Data);


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
  } else if ($Flow == 'addDoctor') {
    $Id = GUId();
    $Cid = $input->Cid;
    $Name = $input->Name;
    $Level = $input->Level;
    $Dept = $input->Dept;
    $Phone =  $input->Phone;
    $sql = "insert into doctor (Id,Cid,Name,Level,Dept,Phone) values ('$Id','$Cid','$Name','$Level','$Dept','$Phone')";

    // var_dump($sql);
    $conn->query($sql);
    $result = $conn->query("select Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime from doctor where Id='$Id'");

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
  } else if ($Flow == 'deleteDoctor') {
    $Id = $input->Id;
    $Cid = $input->Cid;

    $sql = "delete from doctor where Id='$Id'";
    $result = $conn->query($sql);
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
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
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }
  } else if ($Flow == 'listBaby') {
    $Did = $input->Did;

    $sql  = "select * from member where Did = '$Did'";
    $result = $conn->query($sql);
    $members = [];
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($members, $tmp);
      }
    }
    var_dump($members);
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
      "FaultCode" => 0,
      'FaultReason' => 'OK',
      "Data" =>  $doctor
    ]
  );
}
