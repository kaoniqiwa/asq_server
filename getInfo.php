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
  die('不在名单里');
}

$company = getCompany($p_name, $p_pass);
$questions = array();

if (!is_null($company) && $flow == 'getQuestions') {
  $sql_menber = "select Id,Phone,Name,Relation from member where Did in (  select Id from doctor where Cid='$company[Id]')";
  $result_member = $conn->query($sql_menber);
  if ($conn->affected_rows != 0) {
    while ($tmp_member = $result_member->fetch_assoc()) {
      $sql_baby = "select * from baby where Mid='$tmp_member[Id]'";
      $result_baby =  $conn->query($sql_baby);
      if ($conn->affected_rows != 0) {
        while ($tmp_baby = $result_baby->fetch_assoc()) {
          $Id = $tmp_baby['Id'];
          $sql_qus = "select QuestType,QuestScore,QuestMonth,CreateTime from question where Bid='$Id' and  QuestType='asq3' and CreateTime between '$starttime' and '$endtime' ";
          $result_qus =  $conn->query($sql_qus);
          if ($conn->affected_rows != 0) {
            while ($tmp_qus = $result_qus->fetch_assoc()) {
              $tmp_qus['QuestScore'] = json_decode($tmp_qus['QuestScore']);
              $tmp_qus['babyName'] = $tmp_baby['Name'];
              $tmp_qus['babyGender'] = $tmp_baby['gender'];
              $tmp_qus['babyBirthday'] = $tmp_baby['Birthday'];
              $tmp_qus['babySurveyTime'] = $tmp_baby['SurveyTime'];
              $tmp_qus['memberName'] = $tmp_member['Name'];
              $tmp_qus['memberPhone'] = $tmp_member['Phone'];
              $tmp_qus['memberRelation'] = $tmp_member['Relation'];
              $tmp_qus['QuestGames'] = getGames($tmp_qus['QuestMonth']);

              array_push($questions,$tmp_qus);
            }
          }
        }
      }
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
