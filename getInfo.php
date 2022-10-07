<?php
include('./utility/mysql.php');

$p_Name = $_REQUEST['username'];
$p_pass =  $_REQUEST['password'];
// $p_Name = "changhekm";
// $p_pass =  "changhekm";

$json_string = file_get_contents("./getInfo.json");


$grant = json_decode($json_string, true);



for ($i = 0; $i < count($grant); $i++) {
  $account = $grant[$i];
  $username = $account['username'];
  $password = $account['password'];

  if ($username  == $p_Name && $password == $p_pass) {
    break;
  }
}

if ($i >= count($grant)) {
  die('不在名单里');
}

$company = getCompany($p_Name, $p_pass);

if (!is_null($company)) {
  $sql = "select Id,Phone,Name from member where Did in (  select Id from doctor where Cid='$company[Id]')";
  $result = $conn->query($sql);
  $members = [];
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($members, $tmp);
    }
  }

  for ($i = 0; $i < count($members); $i++) {
    $member = $members[$i];

    $sql = "select * from baby where Mid='$member[Id]'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp1 = $result->fetch_assoc()) {
        $Id = $tmp1['Id'];
        $sql = "select * from question where Bid='$Id' and  QuestType='asq3'";
        $result2 =  $conn->query($sql);
        if ($conn->affected_rows != 0) {
          while ($tmp2 = $result2->fetch_assoc()) {
            $tmp1['asq3s'] = array();
            array_push($tmp1['asq3s'], $tmp2);
          }
        }

        $member['babys'] = array();
        array_push($member['babys'], $tmp1);
      }
    }
    $members[$i] = $member;
  }
  echo json_encode(
    [
      "FaultCode" => 0,
      'FaultReason' => 'OK',
      'Data' => $members
    ]
  );
} else {
  die("未查询到该机构信息");
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
