<?php



if (!isset($conn)) {
  $conn = new mysqli('localhost', 'root', 'root');
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset('utf8');
  $conn->select_db('asq');
}


$realm = "neoballoon.com";

$user = new stdClass();
$user->username = 'Neoballoon';
$user->password = '13917805407';


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


  $result = $conn->query("select * from user where username='$user->username' and password='$user->password' limit 1");



  if ($conn->affected_rows != 0) {
    $data =  $result->fetch_assoc();
    echo json_encode(array(
      "faultCode" => 0,
      "fatalReson" => 'OK',
      "data" => $data
      // 'Id' => $datas[0]['id'],
      // 'Username' => $datas[0]['username'],
      // "Name" => $datas[0]['name'],
      // "Grade" => $datas[0]['grade']
    ));
  } else {
    echo json_encode(array(
      "faultCode" => 1,
      'fatalReson' => 'Error',
    ));
  }
}

function authenticate($digest)
{
  global $realm, $nonce, $opaque, $user;
  $headers  = getallheaders();

  $username = '';
  $password = '';

  if (isset($headers["x-webbrowser-authentication"]) && $headers["x-webbrowser-authentication"] == 'Forbidden') {

    $data = http_digest_parse($digest);
    $username = $data['username'];
    $password = "";

    if ($user->username == $username) {
      $password = $user->password;
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
