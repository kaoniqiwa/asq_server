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

  if ($Flow == 'getBabys') {
    $Mid = isset($input->Mid) ? $input->Mid : '';
    $babys = [];
    if($Mid != ''){
      $sql = "select * from baby where Mid= '$Mid'";
      $result = $conn->query($sql);
      
      if ($conn->affected_rows != 0) {
        while ($rs = $result->fetch_assoc()) {
          array_push($babys, $rs);
        }   
      }
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          "Data" =>  $babys
        ]
      );
    }else{
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          "Data" => $babys
        ]
      );
    }
    
    


  }else if ($Flow == 'listBaby') {

    $PageSize = isset($input->PageSize) ? $input->PageSize : 99999;
    $PageIndex = isset($input->PageIndex) ? $input->PageIndex : 1;
    $Start = ($PageIndex - 1) * $PageSize;

    $Name = isset($input->Name) ? $input->Name : "";
    $Mids = isset($input->Mids) ? $input->Mids : [];
    $Ids = isset($input->Ids) ? $input->Ids : [];


    $sql = "select * from baby where Name like '%$Name%'";


    $result = $conn->query($sql);
    $tmp = [];

    if ($conn->affected_rows != 0) {
      while ($rs = $result->fetch_assoc()) {
        array_push($tmp, $rs);
      }
    }

    $tmp2 = [];
    if (count($Mids) == 0) {
      $tmp2 = $tmp;
    } else {
      //$tmp2 = [];
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
    $Gender = $input->Gender;
    $Birthday = getTime($input->Birthday);
    $SurveyTime =  getTime($input->SurveyTime);
    $Premature =  $input->Premature;
    $Prematureweek =  $input->Prematureweek;
    $Prematureday =  $input->Prematureday;
    $Rectifyage =  $input->Rectifyage;
    $IsShun =  $input->IsShun;

    $IdentityInfo =  $input->IdentityInfo;
    $IdentityType =  $input->IdentityType;
    $Weight =  $input->Weight;
    $IsChanqian =  $input->IsChanqian;

    $IsMulti =  $input->IsMulti;
    $OtherAbnormal =  $input->OtherAbnormal;
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());

    $sql = "insert into baby (Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,Prematureweek,Prematureday,Rectifyage,IsShun,IdentityInfo,IdentityType,Weight,IsChanqian,IsMulti,OtherAbnormal,CreateTime,UpdateTime) values ('$Id','$Mid','$Name','$Gender','$Birthday','$SurveyTime','$Premature','$Prematureweek','$Prematureday','$Rectifyage','$IsShun','$IdentityInfo','$IdentityType','$Weight','$IsChanqian','$IsMulti','$OtherAbnormal','$CreateTime','$UpdateTime')";


    $conn->query($sql);
    $result = $conn->query("select * from baby where Id='$Id'");

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
    $Ids = $input->Ids;

    for($i=0;$i<count($Ids);$i++){
      $conn->query("delete from baby where Id='$Ids[$i]'");
    }

    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" => $Ids
      ]
    );

    // $sql = 
  } else if ($Flow == 'updateBaby') {

    $Id = $input->Id;
    $Name = $input->Name;
    $Gender = $input->Gender;
    $Birthday = getTime($input->Birthday);
    $SurveyTime =  getTime($input->SurveyTime);
    $Premature =  $input->Premature;
    $Prematureweek =  $input->Prematureweek;
    $Prematureday =  $input->Prematureday;
    $Rectifyage =  $input->Rectifyage;
    $IsShun =  $input->IsShun;

    $IdentityInfo =  $input->IdentityInfo;
    $IdentityType =  $input->IdentityType;
    $Weight =  $input->Weight;
    $IsChanqian =  $input->IsChanqian;

    $IsMulti =  $input->IsMulti;
    $OtherAbnormal =  $input->OtherAbnormal;
    $UpdateTime  = date('Y-m-d H:i:s', time());


    $sql  = "update baby set Name='$Name',Gender='$Gender',Birthday='$Birthday',SurveyTime='$SurveyTime',Premature='$Premature',Prematureweek='$Prematureweek',Prematureday='$Prematureday',Rectifyage='$Rectifyage',IsShun='$IsShun',IdentityType='$IdentityType',IdentityInfo='$IdentityInfo',Weight='$Weight',IsChanqian='$IsChanqian',IsMulti='$IsMulti',OtherAbnormal='$OtherAbnormal',UpdateTime='$UpdateTime' where Id='$Id'";

    $conn->query($sql);


    $sql  = " select * from baby where Id='$Id'";
    $result =  $conn->query($sql);
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
  }
} else if ($method == 'get') {
  $Id = $_GET['Id'];
  $baby = null;

  $sql =  "select * from baby where Id='$Id'";
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
