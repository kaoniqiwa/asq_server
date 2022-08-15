<?php
include dirname(__DIR__)."/lib/local.php";

date_default_timezone_set('Asia/Shanghai');


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_errno) {
    die('database connect fail');
}
$conn->set_charset(DB_CHAR);
$conn->select_db(DB_NAME);
