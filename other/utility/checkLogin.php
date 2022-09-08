<?php
include "util.php";


session_start();

error_reporting(0);
ini_set('display_errors', 'Off');

if (!isset($_POST['flow']) || $_POST['flow'] != 'login') {
  die('Operation Denied!');
}

$username = $_POST['username'];
$password = $_POST['password'];


$conn->query("select * from user where username='$username'");
//用户不存在
if ($conn->affected_rows == 0) {
  echo json_encode(array(
    "code" => -1,
    'msg' => 'user does not exists!',
  ));
} else {
  $result = $conn->query("select * from user where user='$username' and password = '" . md5($password) . "'");
  if ($conn->affected_rows == 0) {
    echo json_encode(array(
      "code" => 1,
      'msg' => 'password is not correct!',
    ));
  } else {
    $res = $result->fetch_assoc();
    $userInfo = array();
    $userInfo['username'] = $res['user'];
    $userInfo['grade'] = $res['grade'];
    $userInfo['grade_num'] = $res['grade_num'];
    $userInfo['name'] = $res['name'];

    //httponly
    if ($_POST['remember'] == 'true') {
      setcookie('username', $username, time() + 7 * 24 * 60 * 60, '/', '', '', true);
      setcookie('password', $password, time() + 7 * 24 * 60 * 60, '/', '', '', true);
    } else {
      setcookie('username', '', time() - 999, '/', '', '', true);
      setcookie('password', '', time() - 999, '/', '', '', true);
    }
    $_SESSION['isLogin'] = true;
    $_SESSION['userInfo'] = $userInfo;
    echo json_encode(array(
      "code" => 0,
      'msg' => 'success',
    ));
  }
}
$conn->close();
