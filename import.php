<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'asq_1019');
define('DB_CHAR', 'utf8');

if (!isset($conn)) {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset(DB_CHAR);
  $conn->select_db(DB_NAME);
}


//$json_string = file_get_contents("./test.json");
//$grant = json_decode($json_string, true);

$Cid = 'adbcb86a-d694-4c84-83cc-541a3d8604d9';
$QuestType = 'asq3';
$mouthArr = [2, 4, 6, 8, 9, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 42, 48, 54.60];
for ($i = 0; $i < count($grant); $i++) {
  $account = $grant[$i];
  
  $Did = GUId();
  $Cid = $Cid;
  $Name = $account['A88'];
  $Level = '';
  $Dept = '';
  $Phone =  '';
  $CreateTime = date('Y-m-d H:i:s', time());
  $UpdateTime  = date('Y-m-d H:i:s', time());

  $sql = "select * from doctor where Name='$Name' and Cid='$Cid'";
  $db->query($sql);
  $rs = $db->fetch_array();
  if($rs[0] > 0){
    
  }else{
    $sql = "insert into doctor (Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime) values ('$Did','$Cid','$Name','$Level','$Dept','$Phone','$CreateTime','$UpdateTime')";
    $conn->query($sql);
  }

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

  $sql = "select * from member where Phone='$Phone' and Did='$Did'";
  $db->query($sql);
  $rs = $db->fetch_array();
  if($rs[0] > 0){
    
  }else{
    $sql = "insert into member ( Id,Did,Name,Phone,Relation,Province,City,County,Email,IsHelp,HelpInfo,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime) values ('$Mid','$Did','$Name','$Phone','$Relation','$Province','$City','$County','$Email','$IsHelp','$HelpInfo','$PostCode','$Address','$MotherJob','$FatherJob','$MotherDegree','$FatherDegree','$OtherDegree','$MotherBirth','$FatherBirth','$CreateTime','$UpdateTime')";
    $conn->query($sql);
  }

  $Bid = GUID();
  //$Mid = $Mid;
  $Name = $account['A3'];
  $Gender = $account['A4'];
  $Birthday = $account['A5'];
  $SurveyTime =  $account['A6'];
  $Premature =  $account['A9'];
  $Prematrueweek =  $account['A7'];
  $Prematrueday =  $account['A8'];
  $Rectifyage =  '';
  $IsShun =  $account['A12']=='v'?'是':'否';
  $IdentityInfo =  $account['A91'];
  $IdentityType =  $account['A90'];
  $Weight = $account['A19'];
  $IsChanqian =  $account['A14']=='v'?'是':'否';
  $IsMulti =  $account['A16']=='v'?'是':'';
  $OtherAbnormal =  $input->OtherAbnormal;
  $CreateTime = date('Y-m-d H:i:s', time());
  $UpdateTime  = date('Y-m-d H:i:s', time());

  $sql = "select * from baby where Name='$Name' and Mid='$Mid'";
  $db->query($sql);
  $rs = $db->fetch_array();
  if($rs[0] > 0){
    
  }else{
    $sql = "insert into baby (Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,Prematrueweek,Prematrueday,Rectifyage,IsShun,IdentityInfo,IdentityType,Weight,IsChanqian,IsMulti,OtherAbnormal,CreateTime,UpdateTime) values ('$Bid','$Mid','$Name','$Gender','$Birthday','$SurveyTime','$Premature','$Prematrueweek','$Prematrueday','$Rectifyage','$IsShun','$IdentityInfo','$IdentityType','$Weight','$IsChanqian','$IsMulti','$OtherAbnormal','$CreateTime','$UpdateTime')";
    $conn->query($sql);
  }

  //$Bid = $input->Bid;
  $QuestType = $QuestType;
  $QuestMonth = getMonthNumber($account['A36']);
  $QuestResult = json_encode($input->QuestResult);
  $QuestScore = $input->QuestScore;
  $Source = getSource($account['A22']);
  $CreateTime = $account['A6'];
  $UpdateTime  = date('Y-m-d H:i:s', time());

  $sql = "select * from question where Name='$Name' and Mid='$Mid'";
  $db->query($sql);
  $rs = $db->fetch_array();
  if($rs[0] > 0){
    
  }else{
    $sql = "insert into question (Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore,Source,CreateTime,UpdateTime) values ('$Id','$Bid','$QuestType','$QuestMonth','$QuestResult','$QuestScore','$Source','$CreateTime','$UpdateTime')";
    $conn->query($sql);
  }






}


function getMonthNumber($num){
  $mouthArr = [2, 4, 6, 8, 9, 10, 12, 14, 16, 18, 20, 22, 24, 27, 30, 33, 36, 42, 48, 54.60];
  for($i=0;$i<count($mouthArr);$i++){
    if($mouthArr[$i] == $num){
      return $i;
    }
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

//$sql = "insert into question (Id,Bid,QuestType,QuestMonth,QuestResult,QuestScore,CreateTime,UpdateTime) values ('$Id','$Bid','$QuestType','$QuestMonth','$QuestResult','$QuestScore','$CreateTime','$UpdateTime')";
//$sql = "insert into baby (Id,Mid,Name,Gender,Birthday,SurveyTime,Premature,Prematrueweek,Prematrueday,Rectifyage,IsShun,IdentityInfo,IdentityType,Weight,IsChanqian,IsMulti,OtherAbnormal,CreateTime,UpdateTime) values ('$Id','$Mid','$Name','$Gender','$Birthday','$SurveyTime','$Premature','$Prematrueweek','$Prematrueday','$Rectifyage','$IsShun','$IdentityInfo','$IdentityType','$Weight','$IsChanqian','$IsMulti','$OtherAbnormal','$CreateTime','$UpdateTime')";
//$sql = "insert into member ( Id,Did,Name,Phone,Relation,Province,City,County,Email,IsHelp,HelpInfo,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime) values ('$Id','$Did','$Name','$Phone','$Relation','$Province','$City','$County','$Email','$IsHelp','$HelpInfo','$PostCode','$Address','$MotherJob','$FatherJob','$MotherDegree','$FatherDegree','$OtherDegree','$MotherBirth','$FatherBirth','$CreateTime','$UpdateTime')";
//$sql = "insert into doctor (Id,Cid,Name,Level,Dept,Phone,CreateTime,UpdateTime) values ('$Id','$Cid','$Name','$Level','$Dept','$Phone','$CreateTime','$UpdateTime')";

/* for ($i = 0; $i < count($grant); $i++) {
  $account = $grant[$i];
  $A1 = $account['A1'];
  $A2 = $account['A2'];

} */

/* 
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
 */