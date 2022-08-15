<?php
include('../utility/util.php');

$Data = array();

if (!isset($POST['flow']) && !$POST['flow'] == 'company') {
  die('Operation Denied!');
}

$PageSize = $POST['pageSize'];
$PageIndex = $POST['pageIndex'];

$TotalRecortCount = $conn->query("select count(*) from company;")->fetch_assoc()['count(*)'];
$TotalRecortCount = intval($TotalRecortCount);
$PageCount  = ceil($TotalRecortCount / $PageSize);
$RecordCount = 0;

$Start = ($PageIndex - 1) * $PageSize;
$sql = "select * from company limit $Start,$PageSize";
$result = $conn->query($sql);
if ($conn->affected_rows != 0) {
  $RecordCount = $conn->affected_rows;
  while ($rs = $result->fetch_assoc()) {
    array_push($Data, $rs);
  }
}
$conn->close();

echo json_encode(
  array(
    "FatalCode" => 0,
    'FatalReson' => 'OK',
    "Data" => [
      "Data" => $Data,
      "Page" => array(
        "PageCount" => $PageCount,
        "PageSize" => $PageSize,
        "PageIndex" => $PageIndex,
        "RecordCount" => $RecordCount,
        "TotalRecordCount" => $TotalRecortCount
      )
    ]
  )
);
