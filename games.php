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

  if ($Flow == 'getGames') {

    $testid = isset($input->testid) ? $input->testid : 2;
    $sql = "select * from games where testid='$testid' and function<>'' order by function DESC";
    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model = array();
      while($row = mysqli_fetch_assoc($result)){  
        array_unshift($model,$row);
      }
      
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'FaultReason' => $testid,
          'Data' => $model
        ]
      );
    }
    
  }else if ($Flow == 'getDividing') {

    $testid = isset($input->testid) ? $input->testid : 2;
    $sql = "select * from dividing where testid='$testid' and model<>'' order by model DESC";
    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model = array();
      while($row = mysqli_fetch_assoc($result)){  
        array_unshift($model,$row);
      }
      
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'FaultReason' => $testid,
          'Data' => $model
        ]
      );
    }
    
  }

} else if ($method == 'get') {
  $Id = $_GET['Id'];
  $baby = null;

  $sql =  "select Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsChanqian,IsMulti,OtherAbnormal,CreateTime,UpdateTime from baby where Id='$Id'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $baby =  $result->fetch_assoc();
  }
  echo json_encode(
    [
      "FaultCode" => 0,
      'FaultReason' => 'OK',
      "Data" =>  $baby
    ]
  );
}
