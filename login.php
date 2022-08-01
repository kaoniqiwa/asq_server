<?php

include "./lib/home.php";

$realm = "neoballoon.com";

$user1 = new stdClass();
$user1->username = '12345';
$user1->password = '26314548';

$user2 = new stdClass();
$user2->username = 'Neoballoon';
$user2->password = '13917805407';

$users = [
  $user1,
  // $user2
];
$username = '';
$password = '';

if (empty($_SERVER["PHP_AUTH_DIGEST"])) {
  header('WWW-Authenticate: Digest realm="' . $realm .
    '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
  header("HTTP/1.1 403 Forbidden");
  die('登录失败');
}


if (!authenticate($_SERVER["PHP_AUTH_DIGEST"])) {
  header('WWW-Authenticate: Digest realm="' . $realm .
    '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
  header("HTTP/1.1 403 Forbidden");
  die('登录失败');
} else {

  $pass = md5($password);

  $result = $mysqli->query("select * from adminuser where adminname='12345' and password='$pass' limit 1");

  $datas = array();


  if ($mysqli->affected_rows != 0) {
    while ($rs = $result->fetch_assoc()) {
      array_push($datas, $rs);
    }
    echo json_encode(array(
      'Code' => 0,
      'Username' => $datas[0]['adminname'],
      "Name" => $datas[0]['name'],
      "Grade" => $datas[0]['grade']
    ));
  } else {
    echo json_encode(array(
      "Code" => 1
    ));
  }
}

function authenticate($digest)
{
  global $realm, $nonce, $opaque, $users, $username, $password;
  $headers  = getallheaders();

  if (isset($headers["x-webbrowser-authentication"]) && $headers["x-webbrowser-authentication"] == 'Forbidden') {

    $data = http_digest_parse($digest);
    $username = $data['username'];

    foreach ($users as $key => $value) {
      if ($value->username == $username) {
        $password = $value->password;
      }
    }
    if (empty($password)) return false;

    if (isset($data) && isset($password)) {
      $A1 = md5($data['username'] . ':' . $realm . ':' . $password);
      $A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
      $valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);

      if ($valid_response  == $data['response']) {

        return true;
      }
    }
  }

  return false;
}

/**
 * 将字符串转换为数组
 */
function http_digest_parse($digest)
{
  $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
  $data = array();
  $keys = implode('|', array_keys($needed_parts));

  preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $digest, $matches, PREG_SET_ORDER);

  foreach ($matches as $m) {
    $data[$m[1]] = $m[3] ? $m[3] : $m[4];
    unset($needed_parts[$m[1]]);
  }

  return $needed_parts ? false : $data;
}
