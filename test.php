<?php
include('./utility/tool.php');
include('./utility/mysql.php');

error_reporting(1);
ini_set("display_errors","on");

$sql = "select * from baby";
$result = $conn->query($sql);
if ($conn->affected_rows != 0) {
  while ($rs = $result->fetch_assoc()) {
    $Bid = $rs['Id'];
    $sql = "select * from question where Bid = '$Bid'";
    $resultb = $conn->query($sql);
    if ($conn->affected_rows != 0) {
      //$model  =  $result->fetch_assoc();
      $sql = "update baby set Isanswer=1 where Id='$Bid'";
      $conn->query($sql);
    }

  }
}
