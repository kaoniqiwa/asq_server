
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

    if ($oldId == '') {
      $sql = "insert into question (Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore) values ('$Id','$Bid','$QuestType','$QuestMonth','$QuestResult','$QuestScore')";
    } else {
      $sql = "update question set Bid='$Bid',QuestType='$QuestType',QuestMonth='$QuestMonth',QuestResult='$QuestResult',QuestScore='$QuestScore' where Id='$Id'";
    }

    $conn->query($sql);

    $sql = "select Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore ,CreateTime,UpdateTime from question where Id = '$Id'";
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
        "faultCode" => 0,
        'faultReason' => 'OK',
        'data' => $model
      ]
    );
  } else if ($Flow == 'listQuestion') {

    // $PageSize = $input->PageSize;
    // $PageIndex = $input->PageIndex;
    // $Name  = isset($input->Name) ? $input->Name : "";
    // $Start = ($PageIndex - 1) * $PageSize;



    // $TotalRecortCount = $conn->query("select count(*) from question")->fetch_assoc()['count(*)'];



    // $sql = "select id,name,username,password,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left,create_time,update_time from company  where name like '%$name%'  or username like '%$name%' limit $start,$pageSize";

  }
}
