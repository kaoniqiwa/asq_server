<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'asq');
define('DB_CHAR', 'utf8');

if (!isset($conn)) {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset(DB_CHAR);
  $conn->select_db(DB_NAME);
}
