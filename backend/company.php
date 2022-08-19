<?php
include('../utility/util.php');
include('./GUID.php');

$Data = array();

$input = json_decode(file_get_contents('php://input'));

if (!isset($input->flow)) {
  die('Operation Denied!');
}

if ($input->flow == 'listCompany') {
  $pageSize = $input->pageSize;
  $pageIndex = $input->pageIndex;
  $name = '';
  if (isset($input->name)) {
    $name = $input->name;
  }
  $start = ($pageIndex - 1) * $pageSize;


  $sql = "select id,name,account_name,account_pass,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left from company  where name like '%$name%'  or account_name like '%$name%' limit $start,$pageSize";

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


  foreach ($Data as &$value) {
    $id = $value['id'];
    $value['sub_accounts'] = [];
    $sql = "select id,name,level,dept from sub_accounts where c_id ='$id'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      while ($rs = $result->fetch_assoc()) {
        array_push($value['sub_accounts'], $rs);
      }
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
} else if ($input->flow == 'getCompany') {
  $id = $input->id;
  $sql = "select id,name,account_name,account_pass,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left from company where id='$id'";

  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $model  = $result->fetch_assoc();
    $model['sub_accounts'] = [];
    $sql  = "select id,name,level,dept from sub_accounts where c_id ='$id'";

    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      while ($rs = $result->fetch_assoc()) {
        array_push($model['sub_accounts'], $rs);
      }
    }
    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $model
      ]
    );
  }
} else if ($input->flow == 'addCompany') {

  $name = $input->name;
  $account_name = $input->account_name;
  $account_pass = $input->account_pass;
  $asq_total = $input->asq_total;
  $asq_left = $input->asq_left;
  $asq_se_total = $input->asq_se_total;
  $asq_se_left = $input->asq_se_left;
  $asq_se_2_total  = $input->asq_se_2_total;
  $asq_se_2_left  = $input->asq_se_2_left;

  $sql = "insert into company (name,account_name,account_pass,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left) values ('$name','$account_name','$account_pass',$asq_total,$asq_left,$asq_se_total,$asq_se_left,$asq_se_2_total,$asq_se_2_left)";

  $result =   $conn->query($sql);
  if ($conn->affected_rows != 0) {

    $sql = "select id,name,account_name,asq_total,asq_left,asq_se_total,asq_se_left,asq_se_2_total,asq_se_2_left from company  where name='$name'";
    $result2 = $conn->query($sql);
    if ($conn->affected_rows != 0) {

      $model  = $result2->fetch_assoc();
      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          "data" =>  $model
        ]
      );
    }
  }
}
