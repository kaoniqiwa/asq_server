<?php

$arr = [
  array(
    "id" => 1,
    "name" => 'pmx'
  )
];


foreach ($arr as &$value) {
  $value['model'] = 'model';
}
var_dump($arr);
