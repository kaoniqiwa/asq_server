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

var_dump($arr);
