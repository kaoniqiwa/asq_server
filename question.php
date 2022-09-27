
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

  $flow = $input->Flow;


  if ($flow == 'addQuestion') {
    $Id = $input->Id;
    $oldId = $Id;
    if($Id == ''){
      $Id = GUID();
    }

    $Bid = $input->Bid;
    $QuestType = $input->QuestType;
    $QuestMonth = $input->QuestMonth;
    $QuestResult = json_encode($input->QuestResult);
    $QuestScore = $input->QuestScore;

    if($oldId == ''){
      $sql = "insert into asq_test (Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore) values ('$Id','$Bid','$QuestType','$QuestMonth','$QuestResult','$QuestScore')";
    }else{
      $sql = "update asq_test set Bid='$Bid',QuestType='$QuestType',QuestMonth='$QuestMonth',QuestResult='$QuestResult',QuestScore='$QuestScore' where Id='$Id'";
    }

    $conn->query($sql);

    $sql = "select Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore ,CreateTime,UpdateTime from asq_test where Id = '$Id'";
    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      //$model['QuestScore'] =  json_decode($model['QuestScore']);
      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          'data' => $model
        ]
      );
    }
  } else if ($flow == 'getQuestion') {
    $Bid = $input->Bid;
    $QuestType =  isset($input->QuestType) ? $input->QuestType : "";
    $QuestMonth =  isset($input->QuestMonth) ? $input->QuestMonth : "";


    $sql = "select Id,Bid,QuestType,QuestMonth,QuestResult ,CreateTime,UpdateTime  from asq_test where Bid = '$Bid' and QuestType like '%$QuestType%' and QuestMonth like '%$QuestMonth%'";

    $model = [];

    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($model, $tmp);
      }
    }
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        'data' => $model
      ]
    );
  }
}
