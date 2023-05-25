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

include('./utility/tool.php');

if (!isset($conn)) {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset(DB_CHAR);
  $conn->select_db(DB_NAME);
}

$json_string = file_get_contents("./SE2_fl83_230307.json");
$grant = json_decode($json_string, true);

$json_zh = file_get_contents("./dataSE2.json");
$zh_data = json_decode($json_zh, true);

$Cid = '5f499207-afc2-4963-8440-94213b3e6641';
$QuestType = 'ASQ:SE-2';
$mouthArr = [2, 6, 12, 18, 24, 30, 36, 48, 60];


/* echo json_encode(getZongHe(0));

die(); */
echo '$grant_length:'.count($grant).'<br>';
for ($k = 0; $k < count($grant); $k++) {
  $account = $grant[$k];
  
  
  $Did = GUId();
  $Cid = $Cid;
  $Name = $account['A126'];
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
  $Name = $account['A127'];
  $Phone = $account['A24'];
  $Relation = $account['A22'];
  $Province  = $account['A25'];
  $City  = $account['A26'];
  $County  = $account['A27'];
  $Email  = $account['A30'];
  $PostCode  = $account['A29'];
  $Address  = $account['A28'];
  $IsHelp  = '';
  $HelpInfo  = $account['A31'];
  $MotherJob  = '';
  $FatherJob  = '';
  $MotherDegree  = $account['A32'];
  $FatherDegree  = $account['A34'];
  $OtherDegree  = $account['A36'];
  $MotherBirth  = $account['A33'];
  $FatherBirth  = $account['A35'];
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
  $Bname = $account['A4'];
  $Gender = $account['A5'];
  $Birthday = $account['A6'];
  $SurveyTime =  (substr($account['A7'],0,10).' '.substr($account['A7'],10,18));//substr($account['A7'],0,10);
  $Premature =  $account['A10'];
  $Prematureweek =  $account['A8'];
  $Prematureday =  $account['A9'];
  $Rectifyage =  $account['A11'].'月'.$account['A12'].'天';
  $IsShun =  $account['A13']=='v'?'是':'否';
  $IdentityInfo =  $account['A129'];
  $IdentityType =  $account['A128'];
  $Weight = $account['A20'];
  $IsChanqian =  $account['A15']=='v'?'是':'否';
  $IsMulti =  $account['A17']=='v'?'是':'';
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
  $answer_arr = array();
  $worry_arr = array();
  $result_arr = array();
  $nq_arr = array();
  for($j=0;$j<36;$j++){
    if($account['A'.(39+$j*2)] != ''){
      array_push($answer_arr,getAnswer($account['A'.(39+$j*2)]));
    }
    if($account['A'.(39+$j*2+1)] != ''){
      array_push($worry_arr,getWorry($account['A'.(39+$j*2+1)]));
    }
  }
  $nq_arr['nextStatus'] = true;
  $nq_arr['prevStatus'] = false;
  $nq_arr['result'] = $result_arr;
  $nq_arr['answer'] = $answer_arr;
  $nq_arr['worry'] = $worry_arr;
  $nq_arr['score'] = $account['A111'];
  $nq_arr['jiezhi'] = $account['A112'];
  array_push($QuestScore_arr,$nq_arr);


  $QuestMonth = getMonthNumber($account['A38']);
  $ZongHe_arr = array();
  $ZongHe_arr['question'] = getZongHe($QuestMonth);
  $zh_result_arr = array();
  $zh_answer_arr = array();

  array_push($zh_answer_arr,getZhAnswer($account['A130']));
  array_push($zh_answer_arr,getZhAnswer($account['A132']));
  array_push($zh_answer_arr,getZhAnswer($account['A134']));
  array_push($zh_answer_arr,getZhAnswer($account['A131']));
  array_push($zh_answer_arr,getZhAnswer($account['A133']));
  array_push($zh_answer_arr,getZhAnswer($account['A135']));

  array_push($zh_result_arr,getZhResult($account['A130']));
  array_push($zh_result_arr,getZhResult($account['A132']));
  array_push($zh_result_arr,getZhResult($account['A134']));
  array_push($zh_result_arr,getZhResult($account['A131']));
  array_push($zh_result_arr,getZhResult($account['A133']));
  array_push($zh_result_arr,getZhResult($account['A135']));

  $ZongHe_arr['answer'] = $zh_answer_arr;
  $ZongHe_arr['result'] = $zh_result_arr;
  

  $Qid = GUID();
  $QuestScore = json_encode($QuestScore_arr,JSON_UNESCAPED_UNICODE);
  $ZongHe = json_encode($ZongHe_arr,JSON_UNESCAPED_UNICODE);
  $Source = getSource($account['A23']);
  $Status = 0;
  $Importid = $account['A2'];
  $CreateTime = (substr($account['A7'],0,10).' '.substr($account['A7'],10,18));
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

  echo 'success';

}


function getMonthNumber($num){
  $mouthArr = [2, 6, 12, 18, 24, 30, 36, 48, 60];
  for($i=0;$i<count($mouthArr);$i++){
    if($mouthArr[$i] == $num){
      return $i;
    }
  }
}

function getAnswer($str){
  if($str == 'z'){
    return '1';
  }else if($str == 'v'){
    return '2';
  }else{
    return '3';
  }
}

function getZhAnswer($str){
  if($str == '是'){
    return '1';
  }else{
    return '3';
  }
}

function getZhResult($str){
  if($str != '是' && $str !=''){
    return $str;
  }else{
    return '';
  }
}

function getWorry($str){
  if($str == 'no'){
    return 0;
  }else if($str == 'yes'){
    return true;
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
  for ($z = 0; $z < count($this_zh); $z++) {
    if($this_zh[$z][0] == '综合问题'){
      array_push($zonghe_arr,$this_zh[$z+1]);
      array_push($zonghe_arr,$this_zh[$z+2]);
      array_push($zonghe_arr,$this_zh[$z+3]);
      array_push($zonghe_arr,$this_zh[$z+5]);
      array_push($zonghe_arr,$this_zh[$z+6]);
      array_push($zonghe_arr,$this_zh[$z+7]);
    }
    //array_push($zonghe_arr,$this_zh[$z]);
  }
  return $zonghe_arr;
}

?>
</body>
</html>
