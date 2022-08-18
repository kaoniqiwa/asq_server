<?php
include('../utility/util.php');
include('./GUID.php');

$Data = array();

$data = json_decode(file_get_contents('php://input'));

if (!isset($data->flow)) {
  die('Operation Denied!');
}

if ($data->flow == 'listCompany') {
  $pageSize = $data->pageSize;
  $pageIndex = $data->pageIndex;
  $name = '';
  if (isset($data->name)) {
    $name = $data->name;
  }
  $start = ($pageIndex - 1) * $pageSize;


  $sql = " select id,guid,name,account_name,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left from company  where name like '%$name%'  or account_name like '%$name%' limit $start,$pageSize";

  $totalRecortCount = $conn->query("select count(*) from company  where name like '%$name%'")->fetch_assoc()['count(*)'];


  $totalRecortCount = intval($totalRecortCount);
  $PageCount  = ceil($totalRecortCount / $pageSize);
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
      "faultCode" => 0,
      'faultReason' => 'OK',
      "data" => [
        "data" => $Data,
        "page" => array(
          "pageCount" => $PageCount,
          "pageSize" => $pageSize,
          "pageIndex" => $pageIndex,
          "recordCount" => $RecordCount,
          "totalRecordCount" => $totalRecortCount
        )
      ]
    )
  );
} else if ($data->flow == 'addCompany') {
  $guid = GUID();

  $name = $data->name;
  $account_name = $data->account_name;
  $account_pass = $data->account_pass;
  $asq_total = $data->asq_total;
  $asq_left = $data->asq_left;
  $asq_se_total = $data->asq_se_total;
  $asq_se_left = $data->asq_se_left;
  $asq_se_2_total  = $data->asq_se_2_total;
  $asq_se_2_left  = $data->asq_se_2_left;

  $sql = "insert into company (guid,name,account_name,account_pass,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left) values ('$guid','$name','$account_name','$account_pass',$asq_total,$asq_left,$asq_se_total,$asq_se_left,$asq_se_2_total,$asq_se_2_left)";

  $result =   $conn->query($sql);
  if ($conn->affected_rows != 0) {
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
      ]
    );
  }
}
