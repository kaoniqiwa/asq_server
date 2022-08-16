<?php
include('../utility/util.php');

$Data = array();

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->flow)) {
  die('Operation Denied!');
}

if ($data->flow == 'list_company') {
  $PageSize = $data->PageSize;
  $PageIndex = $data->PageIndex;
  $Name = '';
  if (isset($data->Name)) {
    $Name = $data->Name;
  }
  $Start = ($PageIndex - 1) * $PageSize;



  $sql = " select * from company  where name like '%$Name%'  or a_name like '%$Name%' limit $Start,$PageSize";

  $TotalRecortCount = $conn->query("select count(*) from company  where name like '%$Name%'")->fetch_assoc()['count(*)'];


  $TotalRecortCount = intval($TotalRecortCount);
  $PageCount  = ceil($TotalRecortCount / $PageSize);
  $RecordCount = 0;

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
      "FaultCode" => 0,
      'FaultReason' => 'OK',
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
}
