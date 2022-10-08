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

    $PageSize = $input->PageSize;
    $PageIndex = $input->PageIndex;
    $Name = isset($input->Name) ? $input->Name : "";
    $Mids = isset($input->Mids) ? $input->Mids : [];
    $Ids = isset($input->Ids) ? $input->Ids : [];


    $sql = "select Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal,CreateTime,UpdateTime from baby where Name like '%$Name%'";


    $Start = ($PageIndex - 1) * $PageSize;
    $result = $conn->query($sql);
    $tmp = [];

    if ($conn->affected_rows != 0) {
      while ($rs = $result->fetch_assoc()) {
        array_push($tmp, $rs);
      }
    }


    if (count($Mids) == 0) {
      $tmp2 = $tmp;
    } else {
      $tmp2 = [];
      for ($i = 0; $i < count($tmp); $i++) {
        for ($j = 0; $j < count($Mids); $j++) {
          if ($tmp[$i]['Mid'] == $Mids[$j]) {
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
  } else if ($Flow == 'addBaby') {
    $Id = GUID();
    $Mid = $input->Mid;
    $Name = $input->Name;
    $Relation = $input->Relation;
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
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());


    $sql = "insert into baby (Id,Mid,Name,Gender,Relation,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal,CreateTime,UpdateTime) values ('$Id','$Mid','$Name','$Gender','$Relation','$Birthday','$SurveyTime','$Premature','$IsShun','$IdentityInfo','$IdentityType','$Weight','$IsHelp','$IsMulti','$OtherAbnormal','$CreateTime','$UpdateTime')";


    $conn->query($sql);
    $result = $conn->query("select Id,Mid,Name,Gender,Relation,Birthday,SurveyTime,Premature,IsShun,IdentityInfo,IdentityType,Weight,IsHelp,IsMulti,OtherAbnormal,CreateTime,UpdateTime from baby where Id='$Id'");

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
  } else if ($Flow == 'deleteBaby') {
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
