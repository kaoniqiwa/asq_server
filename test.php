<?php

include('./utility/tool.php');


// $data =
//   [
//     "name" => "pm121x"
//   ];
// var_dump(posturl("http://localhost:8888/Project/asq_server/curl.php", $data));


$arr = [
  "Name" => "pmx",
  "auth" => 'han'
];

$arr  = array_change_key_case($arr, CASE_LOWER);

// var_dump($arr);

// var_dump(
//   date('Y-m-d H:i:s', time())
// );

if (!function_exists('getallheaders')) {
  function getallheaders()
  {
    foreach ($_SERVER as $name => $value) {
      if (substr($name, 0, 5) == 'HTTP_') {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }
}

$str = "inser" . date('Y-m-d H:i:s', time());
var_dump($str);
