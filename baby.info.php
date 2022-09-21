<?php
include('./utility/mysql.php');

$json_string = file_get_contents("./company.json");

// var_dump($json_string);

$grant = json_decode($json_string, true);

var_dump($grant);


// $method = strtolower($_SERVER['REQUEST_METHOD']);

// if ($method == 'post') {
//   $input = json_decode(file_get_contents('php://input'));

//   if (!isset($input->flow)) {
//     die('Operation Denied!');
//   }

//   $flow = $input->flow;

//   if ($flow == 'listBaby') {
//   }
// }
