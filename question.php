
<?php
header('content-type:text/json;charset=utf-8');
include('./utility/tool.php');
include('./utility/mysql.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {
  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->Flow)) {
    die('Operation Denied!');
  }

  $Flow = $input->Flow;


  if ($Flow == 'addQuestion') {
    $Id = $input->Id;
    $oldId = $Id;
    if ($Id == '') {
      $Id = GUID();
    }

    $Bid = $input->Bid;
    $QuestType = $input->QuestType;
    $QuestMonth = $input->QuestMonth;
    $QuestResult = json_encode($input->QuestResult);
    $QuestScore = $input->QuestScore;
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());

    if ($oldId == '') {
      $sql = "insert into question (Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore,CreateTime,UpdateTime) values ('$Id','$Bid','$QuestType','$QuestMonth','$QuestResult','$QuestScore','$CreateTime','$UpdateTime')";
    } else {
      $sql = "update question set Bid='$Bid',QuestType='$QuestType',QuestMonth='$QuestMonth',QuestResult='$QuestResult',QuestScore='$QuestScore',UpdateTime='$UpdateTime' where Id='$Id'";
    }

    $conn->query($sql);

    $sql = "select Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore ,CreateTime,UpdateTime from question where Id = '$Id'";
    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      //$model['QuestScore'] =  json_decode($model['QuestScore']);
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }
  } else if ($Flow == 'getQuestion') {
    $Bid = isset($input->Bid) ? $input->Bid : "";
    $QuestType =  isset($input->QuestType) ? $input->QuestType : "";
    $QuestMonth =  isset($input->QuestMonth) ? $input->QuestMonth : "";


    $sql = "select Id,Bid,QuestType,QuestMonth,QuestResult ,CreateTime,UpdateTime  from question where Bid like '%$Bid%' and QuestType like '%$QuestType%' and QuestMonth like '%$QuestMonth%'";

    $model = [];

    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($model, $tmp);
      }
    }
    echo json_encode(
      [
        "FaultReason" => 0,
        'FaultReason' => 'OK',
        'Data' => $model
      ]
    );
  } else if ($Flow == 'listQuestion') {


    $PageSize = isset($input->PageSize) ? $input->PageSize : 99999;
    $PageIndex = isset($input->PageIndex) ? $input->PageIndex : 1;
    $Start = ($PageIndex - 1) * $PageSize;
    // $Bids = ['a26584f8-aa79-48b9-8fee-906025cd983c', 'c0a81994-8c98-4d8c-bff6-188f99503c11'];
    $Bids = isset($input->Bids) ? $input->Bids : [];

    $tmp = changeArr($Bids);


    $sql  = "select Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore,CreateTime,UpdateTime from question where Bid in ($tmp)";


    // var_dump($sql);
    $result = $conn->query($sql);
    $tmp = [];


    if ($conn->affected_rows != 0) {
      while ($rs = $result->fetch_assoc()) {
        array_push($tmp, $rs);
      }
    }

    $TotalRecortCount = count($tmp);
    $Data =  array_slice($tmp, $Start, $PageSize);
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
  }
}


// $projectcode_array = ["20130719", "20130718", "20130717"];
function change_to_quotes($str)
{
  return sprintf("'%s'", $str);
}
function changeArr($arr)
{
  $new_array =  implode(',', array_map('change_to_quotes', $arr));
  return $new_array;
}
// echo changeArr($projectcode_array);
