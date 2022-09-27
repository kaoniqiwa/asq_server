<?php
include('./utility/mysql.php');

$p_name = $_REQUEST['username'];
$p_pass =  $_REQUEST['password'];
// $p_name = "changhekm";
// $p_pass =  "changhekm";

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

if (!is_null($company)) {
  $sql = "select id,phone,member_role,name from member where did in (  select id from doctor where cid='$company[id]')";
  $result = $conn->query($sql);
  $menbers = [];
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($menbers, $tmp);
    }
  }
  
  for ($i = 0; $i < count($menbers); $i++) {
    $menber = $menbers[$i];

    $sql = "select * from baby where mid='$menber[id]'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp1 = $result->fetch_assoc()) {
        $id = $tmp1['id'];
        $sql = "select * from asq_test where bid='$id' and  QuestType='asq3'";
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

/* if (!is_null($company)) {
  $sql = "select * from baby where mid in (select id from member where did in (  select id from doctor where cid='$company[id]'))";
  $result = $conn->query($sql);
  $babys = [];
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($babys, $tmp);
    }
  }
  for ($i = 0; $i < count($babys); $i++) {
    $baby = &$babys[$i];

    $sql = "select * from asq3_test where bid='$baby[id]'";
    $asq3Test = [];
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($asq3Test, $tmp);
      }
    }
    $baby['asq3Test'] = $asq3Test;

    $sql = "select * from asqse_test where bid='$baby[id]'";
    $asqseTest = [];
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($asqseTest, $tmp);
      }
    }
    $baby['asqseTest'] = $asqseTest;

    $sql = "select * from asqse2_test where bid='$baby[id]'";
    $asqse2Test = [];
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($tmp = $result->fetch_assoc()) {
        array_push($asqse2Test, $tmp);
      }
    }
    $baby['asqse2Test'] = $asqse2Test;
  }
  echo json_encode(
    [
      "faultCode" => 0,
      'faultReason' => 'OK',
      'data' => $babys
    ]
  );
} else {
  die("未查询到该机构信息");
} */


function getCompany($username, $password)
{

  global $conn;
  $res = null;
  $sql = "select id,name from company where username ='$username' and  password='$password'";
  $result =  $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $res = $result->fetch_assoc();
  }
  return $res;
}

function getDoctor($cid)
{
  $res = [];

  global $conn;
  $sql = "select id,name from doctor where cid='$cid'";
  $result =  $conn->query($sql);
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($res, $tmp);
    }
  }
  return $res;
}
