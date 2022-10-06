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

  if ($Flow == 'listBaby') {
    $Mid = isset($input->Mid) ? $input->Mid : "";

    $sql = "select Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal,CreateTime,UpdateTime from baby where Mid like '%$Mid%'";

    $babys = [];

    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($babys, $tmp);
      }
    }

    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" =>  $babys
      ]
    );
  } else if ($Flow == 'addBaby') {
    $Id = GUID();
    $Mid = $input->Mid;
    $Name = $input->Name;
    $Gender = $input->Gender;
    $Birthday = getTime($input->Birthday);
    $SurveyTime =  getTime($input->SurveyTime);
    $Premature =  (int)$input->Premature;
    $IsShun =  (int)$input->IsShun;

    $IdentityInfo =  $input->IdentityInfo;
    $IdentityType =  $input->IdentityType;
    $Weight =  $input->Weight;
    $IsHelp =  (int)$input->IsHelp;
    $IsMulti =  (int)$input->IsMulti;
    $OtherAbnormal =  $input->OtherAbnormal;


    $sql = "insert into baby (Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal) values ('$Id','$Mid','$Name','$Gender','$Birthday','$SurveyTime','$Premature','$IsShun','$IdentityInfo','$IdentityType','$Weight','$IsHelp','$IsMulti','$OtherAbnormal')";

    $conn->query($sql);
    $result = $conn->query("select Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal,CreateTime,UpdateTime from baby where Id='$Id'");

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
    // $Id = $input->Id;
    // $cId = $input->cId;

    // $sql = "delete from doctor where Id='$Id'";
    // $result = $conn->query($sql);
    // echo json_encode(
    //   [
    //     "FaultCode" => 0,
    //     'FaultReason' => 'OK',
    //   ]
    // );
  } else if ($Flow == 'editBaby') {
  }
} else if ($method == 'get') {
  $Id = $_GET['Id'];
  $baby = null;

  $sql =  "select Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal,CreateTime,UpdateTime from baby where Id='$Id'";
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
