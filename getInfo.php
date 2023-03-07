<?php
include('./utility/mysql.php');

$p_name = $_REQUEST['username'];
$p_pass =  $_REQUEST['password'];
$flow =  $_REQUEST['flow'];
$starttime =  $_REQUEST['starttime'];
$endtime =  $_REQUEST['endtime'];

if(!$starttime){
  die('开始时间不能为空');
}
if(!$endtime){
  die('结束时间不能为空');
}

$starttime = date("Y-m-d H:i:s",strtotime($starttime));
$endtime = date("Y-m-d H:i:s",strtotime($endtime));


$json_string = file_get_contents("./getInfo.json");


$grant = json_decode($json_string, true);



for ($i = 0; $i < count($grant); $i++) {
  $account = $grant[$i];
  $username = $account['username'];
  $password = $account['password'];

  if ($username  == $p_name && $password == $p_pass) {
    break;
  }
}

if ($i >= count($grant)) {
  die('没有权限');
}

$company = getCompany($p_name, $p_pass);
$questions = array();

if (!is_null($company) && $flow == 'getQuestions') {
  $mphone = $_REQUEST['mphone'];
  $mphoneStr = '';
  if($mphone != ''){
    $mphoneStr = "and member.Phone='".$mphone."'";
  }

  $Uid = $company['Id'];
  $sql_qus= "select question.Id,question.Bid,question.Mid,question.Did,question.Cid,question.QuestMonth,question.QuestScore,question.ZongHe,question.Status,question.QuestType,baby.Name as Bname,baby.Birthday,baby.gender,question.SurveyTime as QsurveyTime,member.Name as Mname,member.Relation,member.Phone from question,baby,member,doctor,company where company.Id='$Uid' and question.Cid='$Uid' and question.Bid=baby.Id and question.Did=doctor.Id and question.Mid=member.Id ".$mphoneStr." and question.SurveyTime between '$starttime' and '$endtime' order by question.CreateTime DESC";
  
  //echo $sql_qus;

  $result_qus =  $conn->query($sql_qus);
  if ($conn->affected_rows != 0) {
    while ($tmp_qus = $result_qus->fetch_assoc()) {
      $question = array();

      $question['babyName'] = $tmp_qus['Bname'];
      $question['babyGender'] = $tmp_qus['gender'];
      $question['babyBirthday'] = $tmp_qus['Birthday'];
      $question['babySurveyTime'] = $tmp_qus['QsurveyTime'];
      $question['memberName'] = $tmp_qus['Mname'];
      $question['memberPhone'] = $tmp_qus['Phone'];
      $question['memberRelation'] = $tmp_qus['Relation'];
      $question['QuestType'] = $tmp_qus['QuestType'];
      $question['QuestMonth'] = $tmp_qus['QuestMonth'];
      $question['QuestGames'] = getGames($tmp_qus['QuestMonth']);
      $question['QuestScore'] = json_decode($tmp_qus['QuestScore']);
      $question['ZongHe'] = json_decode($tmp_qus['ZongHe']);
      $question['QuestReport1'] = setReport($tmp_qus['Cid'],$tmp_qus['Did'],$tmp_qus['Bid'],$tmp_qus['Id'],1);
      $question['QuestReport2'] = setReport($tmp_qus['Cid'],$tmp_qus['Did'],$tmp_qus['Bid'],$tmp_qus['Id'],2);

      array_push($questions,$question);
    }
  }

  echo json_encode(
    [
      "FaultCode" => 0,
      'FaultReason' => 'OK',
      'Data' => $questions
    ]
  );


}else{
  die("未查询到该机构信息");
}

function setReport($uid,$did,$bid,$qid,$type){
  
  $host = $_SERVER['HTTP_HOST'];
  $status = explode($host,'localhost');
  $self = '';
  if(count($status)<=1){
    $self = '/app/asq_frontend';
  }else{
    $host = 'localhost:9200/';
  }
  $url_this = 'http://'.$host.$self;
  $url = $url_this.'/#/asq3print?type='.$type.'&pstatus=2&uid='.$uid.'&did='.$did.'&bid='.$bid.'&qid='.$qid;
  return $url;
}

//var_dump(getGames(0));

function getGames($monthnum){
  $mouthArr = [2, 4, 6, 8, 9, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 42, 48, 54.60];
  $month = $mouthArr[$monthnum];
  $games = array();
  global $conn;
  $sql = "select * from games where testid ='$month' and function<>'' order by function DESC";
  $result =  $conn->query($sql);
  
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($games,$tmp);
    }
  }

  return $games;
}



function getCompany($username, $password)
{

  global $conn;
  $res = null;
  $sql = "select Id,Name from company where Username ='$username' and  Password='$password'";
  $result =  $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $res = $result->fetch_assoc();
  }
  return $res;
}

function getDoctor($cId)
{
  $res = [];

  global $conn;
  $sql = "select Id,Name from doctor where cId='$cId'";
  $result =  $conn->query($sql);
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($res, $tmp);
    }
  }
  return $res;
}
