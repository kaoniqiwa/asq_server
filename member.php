<?php
include('./util.php');


if (!isset($conn)) {
  $conn = new mysqli('localhost', 'root', 'root');
  if ($conn->connect_errno) {
    die('database connect fail');
  }

  $conn->set_charset('utf8');
  $conn->select_db('asq');
}


$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {
  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->flow)) {
    die('Operation Denied!');
  }

  $flow = $input->flow;

  if ($flow == 'listMember') {
    $pageSize = $input->pageSize;
    $pageIndex = $input->pageIndex;
    $name = '';
    if (isset($input->name)) {
      $name = $input->name;
    }
    $start = ($pageIndex - 1) * $pageSize;


    $sql = "select id,name,gender,phone,email,post_code,address,survey_left,create_time,update_time from member  where name like '%$name%'  limit $start,$pageSize";

    $totalRecortCount = $conn->query("select count(*) from member  where name like '%$name%'")->fetch_assoc()['count(*)'];

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


    foreach ($Data as &$value) {
      $id = $value['id'];
      $babys = getBaby($id);
      $value['babys'] = $babys;
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
  } else if ($flow == 'addMember') {
    $id = GUID();
    $did = $input->did;
    $name = $input->name;
    $phone = $input->phone;
    $role  = $input->member_role;

    $sql = "insert into member ( id,did,name,phone,member_role) values ('$id','$did','$name','$phone','$role')";

    $result = $conn->query($sql);
    $sql  = "select id,name,phone,member_role,create_time,update_time from member where id='$id'";

    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          'data' => $model
        ]
      );
    }
  } else if ($flow == 'deleteMember') {
    $id = $input->id;

    $conn->query("delete from baby where mid='$id'");
    $conn->query("delete from member where id='$id'");

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
      ]
    );
  } else if ($flow == 'editMember') {
    $id = $input->id;
    $name = $input->name;
    $gender = $input->gender;
    $phone = $input->phone;
    $email = $input->email;
    $post_code = $input->post_code;
    $address = $input->address;
    $survey_left = $input->survey_left;


    $sql  = "update  member set name='$name',gender='$gender',phone='$phone',email='$email',post_code='$post_code',address='$address',survey_left='$survey_left'  where id='$id'";
    $conn->query($sql);


    $sql  = "select id,name,gender,phone,email,post_code,address,survey_left,create_time,update_time from member where id='$id'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();

      $babys = getBaby($id);
      $model['babys'] = $babys;

      echo json_encode(
        [
          "faultCode" => 0,
          'faultReason' => 'OK',
          'data' => $model
        ]
      );
    }
  }
} else if ($method == 'get') {
  $id = $_GET['id'];

  $sql  = "select id,name,gender,phone,email,post_code,address,survey_left,create_time,update_time from member where id='$id'";

  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $model  = $result->fetch_assoc();

    $babys = getBaby($id);
    $model['babys'] = $babys;

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $model
      ]
    );
  }
}



function getBaby(string $mid)
{
  global $conn;
  $data = [];
  $sql = "select id,mid,name,m_relate,gender,birthday,survey_time,premature,create_time,update_time from baby where mid='$mid'";
  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    while ($tmp = $result->fetch_assoc()) {
      array_push($data, $tmp);
    }
  }
  return $data;
}
