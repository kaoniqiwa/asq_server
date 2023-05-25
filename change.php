<?php
include('./utility/mysql.php');
error_reporting(1);
ini_set("display_errors","on");

$flow =  $_REQUEST['flow'];

/* $p_name = $_REQUEST['username'];
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
 */
if ($flow == 'change') {
  //echo 'xxx';
  $sql = "select * from question where QuestType='ASQ-3'";
  $result = $conn->query($sql);
  $model = [];
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      
      $QuestMonth = $tmp['QuestMonth'];
      $QuestScore_old = $tmp['QuestScore'];
      $QuestScore = json_decode($QuestScore_old,true);
      $dividing = getDividing($QuestMonth);
      $Seq = $tmp['Seq'];
      
      for($i=0;$i<count($QuestScore);$i++){
        
        $thisScore = $QuestScore[$i]['score'];
        $jiezhi = "";
        if($thisScore <= $dividing[$i]['min']){
          $jiezhi = "低于界值";
        }else if($thisScore >  $dividing[$i]['min'] && $thisScore <=  $dividing[$i]['max']){
          $jiezhi = "接近界值";
        }else{
          $jiezhi = "高于界值";
        }
        //echo $jiezhi.'--'.$QuestScore[$i]['jiezhi'];
        if($QuestScore[$i]['jiezhi'] != $jiezhi){
          //echo $i;
          $QuestScore[$i]['jiezhi'] = $jiezhi;
        }
        
        //echo $thisScore."--".$jiezhi."--".$QuestScore[$i]['jiezhi'];
      }
      $QuestScore_new = json_encode($QuestScore,JSON_UNESCAPED_UNICODE);
      //echo $QuestScore_old;
      //echo $QuestScore_new;
      if($QuestScore_new != $QuestScore_old){
        echo $Seq.'<br>';
        $sql = "update question set QuestScore='$QuestScore_new' where Seq='$Seq'";
        $conn->query($sql);
      }else{
        echo 'do not<br>';
      }
      
    }
  }

  //echo json_encode($model);

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

function getDividing($monthnum){
  $mouthArr = [2, 4, 6, 8, 9, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 42, 48, 54,60];
  $month = $mouthArr[(int)$monthnum];
  $Dividing = array();
  global $conn;
  $sql = "select * from dividing where testid ='$month' and typeid=1 order by id ASC";
  $result =  $conn->query($sql);
  
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      //array_push($Dividing,$tmp);
      $Dividing[] = $tmp;
    }
  }

  return $Dividing;
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
