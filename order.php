<?php

include('./utility/tool.php');
include('./utility/mysql.php');


$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {
  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->flow)) {
    die('Operation Denied!');
  }

  $flow = $input->flow;

  if ($flow == 'listOrder') {

    $pageSize = $input->pageSize;
    $pageIndex = $input->pageIndex;
    $phone = '';
    if (isset($input->phone)) {
      $phone = $input->phone;
    }
    $start = ($pageIndex - 1) * $pageSize;


    $sql = "select id,name,phone,order_type,price ,create_time from orders  where phone  like '%$phone%' limit $start,$pageSize";

    $totalRecortCount = $conn->query("select count(*) from orders  where phone  like '%$phone%'")->fetch_assoc()['count(*)'];


    $totalRecortCount = intval($totalRecortCount);
    $PageCount  = ceil($totalRecortCount / $pageSize);
    $RecordCount = 0;

    $result = $conn->query($sql);
    $Data = array();

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }




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
  } else if ($flow == 'addOrder') {
    // $id = GUID();
    // $cid = $input->cid;
    // $name = $input->name;
    // $level = $input->level;
    // $dept = $input->dept;
    // $phone =  $input->phone;
    // $sql = "insert into Order (id,cid,name,level,dept,phone) values ('$id','$cid','$name','$level','$dept','$phone')";

    // $conn->query($sql);
    // $result = $conn->query("select id,cid,name,level,dept,create_time from Order where id='$id'");

    // if ($conn->affected_rows != 0) {
    //   $model  =  $result->fetch_assoc();
    //   echo json_encode(
    //     [
    //       "faultCode" => 0,
    //       'faultReason' => 'OK',
    //       'data' => $model
    //     ]
    //   );
    // }
  } else if ($flow == 'deleteOrder') {
    $id = $input->id;

    $sql  = "select id,name,phone,order_type,price ,create_time from orders where id='$id' ";
    $result = $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $model = $result->fetch_assoc();

      $sql = "delete from orders where id='$id'";
      $result = $conn->query($sql);
      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          "data" => $model
        ]
      );
    } else {
      echo json_encode(
        [
          "faultCode" => 1,
          'faultReason' => 'Error',
        ]
      );
    }
  } else if ($flow == 'editOrder') {
    // $id = $input->id;
    // $name = $input->name;
    // $level = $input->level;
    // $dept = $input->dept;
    // $phone =  $input->phone;
    // $sql  = "update Order set name='$name',level='$level',dept='$dept',phone='$phone' where id='$id'";
    // $conn->query($sql);

    // $result = $conn->query("select id,cid,name,level,dept,create_time from Order where id='$id'");

    // if ($conn->affected_rows != 0) {
    //   $model  =  $result->fetch_assoc();
    //   echo json_encode(
    //     [
    //       "faultCode" => 0,
    //       'faultReason' => 'OK',
    //       'data' => $model
    //     ]
    //   );
    // }
  } else if ($flow == 'exportOrder') {
    $beginTime = getTime($input->beginTime);
    $endTime = getTime($input->endTime);

    $sql  = "select id,name,phone,order_type,price ,create_time from orders where create_time between '$beginTime' and '$endTime'";

    $result = $conn->query($sql);
    $Data = [];

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }


    // foreach ($Data as &$value) {
    //   $id = $value['id'];
    //   $doctors = getDoctor($id);
    //   $value['doctors'] = $doctors;
    // }

    echo json_encode(
      array(
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" => $Data
      )
    );
  }
} else if ($method == 'get') {
  // $id = $_GET['id'];
  // $Order = getOrder($id);

  // echo json_encode(
  //   [
  //     "faultCode" => 0,
  //     'faultReason' => 'OK',
  //     "data" =>  $Order
  //   ]
  // );
}


function getOrder(string $id)
{
  global $conn;
  $sql =  "select id,cid,name,level,dept,create_time from Order where id='$id'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    return $result->fetch_assoc();
  }
  return null;
}

function listOrder(string $cid)
{
  global $conn;
  $data = [];
  $sql = "select id,cid,name,level,dept,create_time from Order where cid='$cid' ";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($data, $tmp);
    }
  }
  return $data;
}
