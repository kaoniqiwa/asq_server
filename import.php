<!DOCTYPE html>
<html>
 <head>
  <title> show_pape </title>
  <meta name="Generator" content="EditPlus">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>
 <body>
<?php
header("Content-Type:text/html;charset=utf-8");
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'asq');
define('DB_CHAR', 'utf8');

error_reporting(1);
ini_set("display_errors","on");
ini_set('max_execution_time',7200);
ini_set('memory_limit',-1);
//C(‘REST_TIMEOUT’,360);

include('./utility/tool.php');

if (!isset($conn)) {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset(DB_CHAR);
  $conn->select_db(DB_NAME);
}

$json_string = file_get_contents("./Xxjd20230318.json");
$grant = json_decode($json_string, true);

$json_zh = file_get_contents("./data.json");
$zh_data = json_decode($json_zh, true);

$Cid = '3b897c6b-53c0-415d-9080-cd530b769da1';
$QuestType = 'ASQ-3';
$mouthArr = [2, 4, 6, 8, 9, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 42, 48, 54,60];
echo '$grant_length:'.count($grant).'<br>';
for ($k = 0; $k < count($grant); $k++) {
  $account = $grant[$k];
  
  
  $Did = GUId();
  $Cid = $Cid;
  $Name = $account['A88'];
  $Level = '';
  $Dept = '';
  $Phone =  '';
  $CreateTime = date('Y-m-d H:i:s', time());
  $UpdateTime  = date('Y-m-d H:i:s', time());

  
  $sql = "select * from doctor where Name='$Name' and Cid='$Cid' LIMIT 1";
  $result = $conn->query($sql);
  if($conn->affected_rows > 0){
    echo 'doctor<br>';
    $doctor =  $result->fetch_assoc();
    $Did = $doctor['Id'];
  }else{
    $sql = "insert into doctor (Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime) values ('$Did','$Cid','$Name','$Level','$Dept','$Phone','$CreateTime','$UpdateTime')";
    $conn->query($sql);
  }

  //echo $account['A88'].'阿萨德<br>';
  //exit();

  $Mid = GUID();
  //$Did = $input->Did;
  $Name = $account['A20'];
  $Phone = $account['A23'];
  $Relation = $account['A21'];
  $Province  = $account['A24'];
  $City  = $account['A25'];
  $County  = $account['A26'];
  $Email  = $account['A29'];
  $PostCode  = $account['A28'];
  $Address  = $account['A27'];
  $IsHelp  = '';
  $HelpInfo  = $account['A30'];
  $MotherJob  = '';
  $FatherJob  = '';
  $MotherDegree  = $account['A31'];
  $FatherDegree  = $account['A33'];
  $OtherDegree  = $account['A35'];
  $MotherBirth  = $account['A32'];
  $FatherBirth  = $account['A34'];
  $CreateTime = date('Y-m-d H:i:s', time());
  $UpdateTime  = date('Y-m-d H:i:s', time());

  $sql = "select * from member where Phone='$Phone'";
  $result = $conn->query($sql);
  if($conn->affected_rows > 0){
    echo 'member<br>';
    $member =  $result->fetch_assoc();
    $Mid = $member['Id'];
  }else{
    $sql = "insert into member ( Id,Did,Name,Phone,Relation,Province,City,County,Email,IsHelp,HelpInfo,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime) values ('$Mid','$Did','$Name','$Phone','$Relation','$Province','$City','$County','$Email','$IsHelp','$HelpInfo','$PostCode','$Address','$MotherJob','$FatherJob','$MotherDegree','$FatherDegree','$OtherDegree','$MotherBirth','$FatherBirth','$CreateTime','$UpdateTime')";
    $conn->query($sql);
  }

  $Bid = GUID();
  //$Mid = $Mid;
  $Bname = $account['A3'];
  $Gender = $account['A4'];
  $Birthday = $account['A5'];
  //$SurveyTime =  substr($account['A6'],0,10);
  $SurveyTime =  $account['A6'];
  $Premature =  $account['A9'];
  $Prematureweek =  $account['A7'];
  $Prematureday =  $account['A8'];
  $Rectifyage =  $account['A10'].'月'.$account['A11'].'天';
  $IsShun =  $account['A12']=='v'?'是':'否';
  $IdentityInfo =  $account['A91'];
  $IdentityType =  $account['A90'];
  $Weight = $account['A19'];
  $IsChanqian =  $account['A14']=='v'?'是':'否';
  $IsMulti =  $account['A16']=='v'?'是':'';
  $OtherAbnormal =  '';
  $CreateTime = date('Y-m-d H:i:s', time());
  $UpdateTime  = date('Y-m-d H:i:s', time());

  $sql = "select * from baby where Name='$Bname' and Mid='$Mid'";
  $result = $conn->query($sql);
  if($conn->affected_rows > 0){
    echo 'baby<br>';
    $baby =  $result->fetch_assoc();
    $Bid = $baby['Id'];
  }else{
    $sql = "insert into baby (Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,Prematureweek,Prematureday,Rectifyage,IsShun,IdentityInfo,IdentityType,Weight,IsChanqian,IsMulti,OtherAbnormal,CreateTime,UpdateTime) values ('$Bid','$Mid','$Bname','$Gender','$Birthday','$SurveyTime','$Premature','$Prematureweek','$Prematureday','$Rectifyage','$IsShun','$IdentityInfo','$IdentityType','$Weight','$IsChanqian','$IsMulti','$OtherAbnormal','$CreateTime','$UpdateTime')";
    $conn->query($sql);

    //echo $sql.'<br>';

  }
  
  $QuestScore_arr = array();
  for($i=0;$i<5;$i++){
    $answer_arr = array();
    $result_arr = array();
    $nq_arr = array();
    for($j=0;$j<8;$j++){
      if($j<6){
        array_push($answer_arr,getAnswer($account['A'.(37+$j+$i*8)]));
      }
      if($j == 6){
        //echo 'AA'.(37+$j+$i*5).'<br>';
        $nq_arr['score'] = $account['A'.(37+$j+$i*8)];
      }
      if($j == 7){
        //echo 'AA'.(37+$j+$i*5).'<br>';
        $nq_arr['jiezhi'] = $account['A'.(37+$j+$i*8)];
      }
    }

    $nq_arr['nextStatus'] = true;
    $nq_arr['prevStatus'] = true;
    $nq_arr['answer'] = $answer_arr;
    $nq_arr['result'] = $result_arr;

    if($i==0){
      $nq_arr['nengqu'] = '沟通';
      $nq_arr['prevStatus'] = false;
    }else if($i==1){
      $nq_arr['nengqu'] = '粗大动作';
    }else if($i==2){
      $nq_arr['nengqu'] = '精细动作';
    }else if($i==3){
      $nq_arr['nengqu'] = '解决问题';
    }else if($i==4){
      $nq_arr['nengqu'] = '个人-社会';
    }
    
    array_push($QuestScore_arr,$nq_arr);
  }

  $QuestMonth = getMonthNumber($account['A36']);
  $ZongHe_arr = array();
  $ZongHe_arr['question'] = getZongHe($QuestMonth);
  $zh_result_arr = [];
  $zh_answer_arr = [];
  for($n=0;$n< count($ZongHe_arr['question'])-1;$n++){
    
    array_push($zh_result_arr,$account['A'.(77+$n)]==null?'':$account['A'.(77+$n)]);
    array_push($zh_answer_arr,setZongHeAn($account['A'.(77+$n)],$ZongHe_arr['question'][$n+1][2]));
  }
  $ZongHe_arr['result'] = $zh_result_arr;
  $ZongHe_arr['answer'] = $zh_answer_arr;

  $Qid = GUID();
  $QuestScore = json_encode($QuestScore_arr,JSON_UNESCAPED_UNICODE);
  $ZongHe = json_encode($ZongHe_arr,JSON_UNESCAPED_UNICODE);
  $Source = getSource($account['A22']);
  $Status = 0;
  $Importid = $account['A2'];
  //$CreateTime = substr($account['A6'],0,10);
  $CreateTime = $account['A6'];
  $UpdateTime  = date('Y-m-d H:i:s', time());

  $sql = "select * from question where Importid='$Importid' and Bid='$Bid'";
  $result = $conn->query($sql);
  if($conn->affected_rows > 0){
    echo 'question<br>';
    $question =  $result->fetch_assoc();
    $Qid = $question['Id'];
  }else{
    $sql = "insert into question (Id,Importid,Cid,Did,Mid,Bid,QuestType,QuestMonth,QuestScore,ZongHe,Source,SurveyTime,Rectifyage,CreateTime,UpdateTime) values ('$Qid','$Importid','$Cid','$Did','$Mid','$Bid','$QuestType','$QuestMonth','$QuestScore','$ZongHe','$Source','$SurveyTime','$Rectifyage','$CreateTime','$UpdateTime')";
    $conn->query($sql);
    //echo $sql;
  }

}


function getMonthNumber($num){
  $mouthArr = [2, 4, 6, 8, 9, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 42, 48, 54,60];
  for($i=0;$i<count($mouthArr);$i++){
    if($mouthArr[$i] == $num){
      return $i;
    }
  }
}

function getAnswer($str){
  if($str == '是'){
    return '1';
  }else if($str == '有时是'){
    return '2';
  }else{
    return '3';
  }
}

function getSource($str){
  if($str == '直接答题'){
    return 1;
  }else if($str == '扫码答题'){
    return 2;
  }else{
    return 3;
  }
}

function getZongHe($monthNum){
  global $zh_data;
  $this_zh = $zh_data[$monthNum]['data'];
  $zonghe_arr = [];
  for ($z = 41; $z < count($this_zh); $z++) {
    array_push($zonghe_arr,$this_zh[$z]);
  }
  return $zonghe_arr;
}

function setZongHeAn($con,$an){
  if($con!=null){
    if($an=='是'){
      return 1;
    }else{
      return 3;
    }
  }else{
    if($an=='是'){
      return 3;
    }else{
      return 1;
    }
  }
}

?>
</body>
</html>
