<?php
$input = json_decode(file_get_contents('php://input'));
$name = $input->name;
echo json_encode(array("name" => $name));
