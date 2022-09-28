<?php
include('./utility/tool.php');
include('./utility/mysql.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method == 'post') {
  $input = json_decode(file_get_contents('php://input'));

  if (!isset($input->Flow)) {
    die('Operation Denied!');
  }

  $Flow = $input->Flow;

  if ($Flow == 'listMember') {
    $PageSize = $input->PageSize;
    $PageIndex = $input->PageIndex;
    $Name = '';
    if (isset($input->Name)) {
      $Name = $input->Name;
    }
    $start = ($PageIndex - 1) * $PageSize;


    $sql = "select Id,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member  where Name like '%$Name%'  limit $start,$PageSize";


    $totalRecortCount = $conn->query("select count(*) from member  where Name like '%$Name%'")->fetch_assoc()['count(*)'];

    $totalRecortCount = intval($totalRecortCount);
    $PageCount  = ceil($totalRecortCount / $PageSize);
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
            "PageSize" => $PageSize,
            "PageIndex" => $PageIndex,
            "recordCount" => $RecordCount,
            "totalRecordCount" => $totalRecortCount
          )
        ]
      )
    );
  } else if ($Flow == 'addMember') {
    $Id = GUID();
    $Did = $input->Did;
    $Name = $input->Name;
    $Phone = $input->Phone;


    $Province  = $input->Province;
    $City  = $input->City;
    $County  = $input->County;
    $Email  = $input->Email;
    $PostCode  = $input->PostCode;
    $Address  = $input->Address;
    $MotherJob  = $input->MotherJob;
    $FatherJob  = $input->FatherJob;
    $MotherDegree  = $input->MotherDegree;
    $FatherDegree  = $input->FatherDegree;
    $OtherDegree  = $input->OtherDegree;
    $MotherBirth  = $input->MotherBirth;
    $FatherBirth  = $input->FatherBirth;

    $sql = "insert into member ( Id,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth) values ('$Id','$Did','$Name','$Phone','$role','$Province','$City','$County','$Email','$PostCode','$Address','$MotherJob','$FatherJob','$MotherDegree','$FatherDegree','$OtherDegree','$MotherBirth','$FatherBirth')";

    $result = $conn->query($sql);
    $sql  = "select Id,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";

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
  } else if ($Flow == 'deleteMember') {
    $Id = $input->Id;

    $conn->query("delete from baby where Mid='$Id'");
    $conn->query("delete from member where Id='$Id'");

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
      ]
    );
  } else if ($Flow == 'editMember') {
    $Id = $input->Id;
    $Name = $input->Name;
    $gender = $input->gender;
    $Phone = $input->Phone;
    $Email = $input->Email;
    $PostCode = $input->PostCode;
    $Address = $input->Address;
    $survey_left = $input->survey_left;


    $sql  = "update  member set Name='$Name',Phone='$Phone',Email='$Email',PostCode='$PostCode',Address='$Address'  where Id='$Id'";
    $conn->query($sql);


    $sql  = "selectId,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";
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
  }
} else if ($method == 'get') {
  $Id = $_GET['Id'];

  $sql  = "select Id,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime  from member where Id='$Id'";

  $result = $conn->query($sql);
  if ($conn->affected_rows != 0) {
    $model  = $result->fetch_assoc();

    echo json_encode(
      [
        "faultCode" => 0,
        'faultReason' => 'OK',
        "data" =>  $model
      ]
    );
  }
}
