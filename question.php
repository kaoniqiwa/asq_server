
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
    $Cid = $input->Cid;
    $Did = $input->Did;
    $Mid = $input->Mid;
    $QuestType = $input->QuestType;
    $QuestMonth = $input->QuestMonth;
    //$QuestResult = $input->QuestResult;
    $QuestScore = $input->QuestScore;
    $ZongHe = $input->ZongHe;
    $Source = $input->Source;
    $SurveyTime = $input->SurveyTime;
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());

    //if ($oldId == '') {
      $sql = "insert into question (Id,Cid,Did,Mid,Bid,QuestType,QuestMonth,QuestScore,ZongHe,Source,SurveyTime,CreateTime,UpdateTime) values ('$Id','$Cid','$Did','$Mid','$Bid','$QuestType','$QuestMonth','$QuestScore','$ZongHe','$Source','$SurveyTime','$CreateTime','$UpdateTime')";
      //echo $sql.'--------------------------------------------';
    /* } else {
      $sql = "update question set Cid='$Cid',Did='$Did',Mid='$Mid',Bid='$Bid',QuestType='$QuestType',QuestMonth='$QuestMonth',QuestScore='$QuestScore',ZongHe='$ZongHe',Source='$Source',SurveyTime='$SurveyTime',UpdateTime='$UpdateTime' where Id='$Id'";
    } */

    $conn->query($sql);
    
    $sql = "update baby set Isanswer=1 where Id='$Bid'";
    $conn->query($sql);

    $sql = "select Id,Cid,Did,Mid,Bid,QuestType,QuestMonth,QuestScore,ZongHe,Source,SurveyTime,CreateTime,UpdateTime from question where Id = '$Id'";
    $result = $conn->query($sql);
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
  } else if ($Flow == 'getQuestion') {
    $Bid = isset($input->Bid) ? $input->Bid : "";
    $QuestType =  isset($input->QuestType) ? $input->QuestType : "";
    $QuestMonth =  isset($input->QuestMonth) ? $input->QuestMonth : "";


    $sql = "select Id,Cid,Did,Mid,Bid,QuestType,QuestMonth,QuestScore,ZongHe,Source,SurveyTime,CreateTime,UpdateTime  from question where Bid like '%$Bid%' and QuestType like '%$QuestType%' and QuestMonth like '%$QuestMonth%'";

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
  } else if ($Flow == 'getQuestionByBaby') {
    $Bids = isset($input->Bids) ? $input->Bids : [];
    $Uid = isset($input->Uid) ? $input->Uid : '';
    $tmp = changeArr($Bids);
    $uidStr = '';
    if($Uid != ''){
      $uidStr = "and question.Cid='".$Uid."'";
    }

    $sql = "select Id,Cid,Did,Mid,Bid,QuestType,QuestMonth,Source,SurveyTime,CreateTime,UpdateTime  from question where Bid in ($tmp) ".$nameStr." order by CreateTime DESC";

    //echo $sql;

    $model = [];

    $result = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($model, $tmp);
      }
    }
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $model
      ]
    );
  
  } else if ($Flow == 'listQuestion') {


    $PageSize = isset($input->PageSize) ? $input->PageSize : 99999;
    $PageIndex = isset($input->PageIndex) ? $input->PageIndex : 1;
    $Start = ($PageIndex - 1) * $PageSize;
    // $Bids = ['a26584f8-aa79-48b9-8fee-906025cd983c', 'c0a81994-8c98-4d8c-bff6-188f99503c11'];
    $Uid = isset($input->Uid) ? $input->Uid : '';
    $Dids = isset($input->Dids) ? $input->Dids : [];
    $Name = isset($input->Name) ? $input->Name : '';
    $QuestMonth = isset($input->QuestMonth) ? $input->QuestMonth : '';
    $Status = isset($input->Status) ? $input->Status : '';
    $BeginTime = isset($input->BeginTime) ? $input->BeginTime : '';
    $EndTime = isset($input->EndTime) ? $input->EndTime : '';
    
    $nameStr = '';
    if($Name != ''){
      $nameStr = "and baby.Name like '%".$Name."%'";
    }
    $didstr = '';
    if(count($Dids) > 0 && $Dids != ''){
      $tmpp = changeArr($Dids);
      $didstr = "and question.Did in ($tmpp)";
    }
    $statusStr = '';
    if($Status != '' && $Status != 3){
      $statusStr = "and question.Status='".$Status."'";
    }
    $questmonthStr = '';
    if($QuestMonth != ''){
      $questmonthStr = "and question.QuestMonth='".$QuestMonth."'";
    }
    $timeStr = '';
    if($BeginTime != '' && $EndTime != ''){
      $timeStr = "and question.SurveyTime between '$BeginTime' and '$EndTime'";
    }

    //echo count($Dids)."--".$Dids."--".$nameStr."--".$statusStr."--".$questmonthStr."--".$timeStr;
    
    $sql = '';
    $sql= "select question.Id,question.Bid,question.Mid,question.Did,question.Cid,question.QuestMonth,question.Status,baby.Name,baby.Birthday,question.SurveyTime,member.Name as Mname from question,baby,member where question.Cid='$Uid' and question.Bid=baby.Id and question.Mid=member.Id ".$didstr." ".$nameStr." ".$statusStr." ".$questmonthStr." ".$timeStr." order by question.CreateTime DESC";

    /* if($Uid != ''){
      $sql= "select question.Id,question.Bid,question.Mid,question.Did,question.Cid,question.QuestMonth,question.Status,baby.Name,baby.Birthday,question.SurveyTime,member.Name as Mname from question,baby,member where question.Cid='$Uid' and question.Bid=baby.Id and question.Mid=member.Id ".$nameStr." order by question.CreateTime DESC";
    }else{
      
      $sql= "select question.Id,question.Bid,question.Mid,question.Did,question.Cid,question.QuestMonth,question.Status,baby.Name,baby.Birthday,question.SurveyTime,member.Name as Mname from question,baby,member where question.Cid='$Uid' and question.Bid=baby.Id and question.Mid=member.Id ".$didstr." ".$nameStr." ".$statusStr." ".$questmonthStr." ".$timeStr." order by question.CreateTime DESC";
      
    } */
    //echo "--".$sql;
    
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
  } else if ($Flow == 'changeStatus') {


    $Qid = isset($input->Qid) ? $input->Qid : '';
    $Status = isset($input->Status) ? $input->Status : 0;

    //echo $Qid."---".$Status."<br>";
    
    $sql = "update question set Status='$Status' where Id='$Qid'";
    $model = array();
    $model['Status'] = $Status;
    $result = $conn->query($sql);
    $model['result'] = $result;
    //if ($conn->affected_rows != 0) {
      //$model  =  $result->fetch_assoc();
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    //}
  }
}else if ($method == 'get') {
  $Id = $_GET['Id'];
  $baby = null;
  
  $sql = "select * from question where Id='$Id' ";
  //$sql= "select a.Id,a.Bid,a.Did,a.Uid,b.Name from question as a, baby as b where a.Bid=b.id and a.Id='$Id'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $question =  $result->fetch_assoc();
  }
  echo json_encode(
    [
      "FaultCode" => 0,
      'FaultReason' => 'OK',
      "Data" =>  $question
    ]
  );

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
