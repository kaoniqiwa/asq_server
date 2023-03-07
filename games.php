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

    $testid = isset($input->TestId) ? $input->TestId : 2;
    $sql = "select * from games where testid='$testid' and function<>'' order by function ASC";
    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model = array();
      $jc = array();
      $gt = array();
      $cddz = array();
      $jxdz = array();
      $jjwt = array();
      $grsh = array();
      while($row = mysqli_fetch_assoc($result)){ 
        array_push($jc,$row['standard']);
        //array_push($model,$row);
        
        if($row['function'] == 1){
          $gt = getGames($conn,$row,$gt,1);
        }
        if($row['function'] == 2){
          $cddz = getGames($conn,$row,$cddz,2);
        }
        if($row['function'] == 3){
          $jxdz = getGames($conn,$row,$jxdz,3);
        }
        if($row['function'] == 4){
          $jjwt = getGames($conn,$row,$jjwt,4);
        }
        if($row['function'] == 5){
          $grsh = getGames($conn,$row,$grsh,5);
        }
        
      }
      array_push($model,$jc);
      array_push($model,$gt);
      array_push($model,$cddz);
      array_push($model,$jxdz);
      array_push($model,$jjwt);
      array_push($model,$grsh);
/* 
      $model['jc'] = $jc;
      $model['gt'] = $gt;
      $model['cddz'] = $cddz;
      $model['jxdz'] = $jxdz;
      $model['jjwt'] = $jjwt;
      $model['grsh'] = $grsh;
      $model['testid'] = $testid; */
      
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }
    
  }else if ($Flow == 'getDividing') {

    $testid = isset($input->TestId) ? $input->TestId : 2;
    $typeid = isset($input->TypeId) ? $input->TypeId : 1;
    $sql = "select * from dividing where testid='$testid' and typeid='$typeid' ";
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


function getGames($conn,$row,$Arr,$num){
  if($row['function'] == $num){//standardbak1
    $sql1 = "select * from games where testid=".$row['standardbak1']." and function='$num' order by function ASC";
    $result1 = $conn->query($sql1);
    while($row1 = mysqli_fetch_assoc($result1)){
      array_push($Arr,$row1['standard']);
    }

    $sql3 = "select * from games where testid=".$row['bak']." and function='$num' order by function ASC";
    $result3 = $conn->query($sql3);
    while($row3 = mysqli_fetch_assoc($result3)){
      if($row3['backup1']!=''){
        array_push($Arr,$row3['backup1']);
      }
      if($row3['backup2']!=''){
        array_push($Arr,$row3['backup2']);
      }
      if($row3['backup3']!=''){
        array_push($Arr,$row3['backup3']);
      }
      if($row3['backup4']!=''){
        array_push($Arr,$row3['backup4']);
      }
      if($row3['backup5']!=''){
        array_push($Arr,$row3['backup5']);
      }
      if($row3['backup6']!=''){
        array_push($Arr,$row3['backup6']);
      }
      if($row3['backup7']!=''){
        array_push($Arr,$row3['backup7']);
      }
      if($row3['backup8']!=''){
        array_push($Arr,$row3['backup8']);
      }
      if($row3['backup9']!=''){
        array_push($Arr,$row3['backup9']);
      }
      if($row3['backup10']!=''){
        array_push($Arr,$row3['backup10']);
      }
    }

    $sql2 = "select * from games where testid=".$row['standardbak2']." and function='$num' order by function ASC";
    $result2 = $conn->query($sql2);
    while($row2 = mysqli_fetch_assoc($result2)){
      array_push($Arr,$row2['standard']);
    }
    
  }

  return $Arr;
}