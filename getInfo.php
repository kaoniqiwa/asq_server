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
  $menbers = [];
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($menbers, $tmp);
    }
  }

  for ($i = 0; $i < count($menbers); $i++) {
    $menber = $menbers[$i];

    $sql = "select * from baby where Mid='$menber[Id]'";
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

        $menber['babys'] = array();
        array_push($menber['babys'], $tmp1);
      }
    }
    $menbers[$i] = $menber;
  }
  echo json_encode(
    [
      "faultCode" => 0,
      'faultReason' => 'OK',
      'data' => $menbers
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
