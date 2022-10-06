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


    $TotalRecortCount = $conn->query("select count(*) from member  where Name like '%$Name%'")->fetch_assoc()['count(*)'];

    $TotalRecortCount = intval($TotalRecortCount);
    $PageCount  = ceil($TotalRecortCount / $PageSize);
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
  } else if ($Flow == 'addMember') {
    $Id = GUID();
    $Did = $input->Did;
    $Name = $input->Name;
    $Phone = $input->Phone;


    $Province  = isset($input->Province) ? $input->Province : "";
    $City  = isset($input->City) ? $input->City : "";
    $County  = isset($input->County) ? $input->County : "";
    $Email  = isset($input->Email) ? $input->Email : "";
    $PostCode  = isset($input->PostCode) ? $input->PostCode : "";
    $Address  = isset($input->Address) ? $input->Address : "";
    $MotherJob  = isset($input->MotherJob) ? $input->MotherJob : "";
    $FatherJob  = isset($input->FatherJob) ? $input->FatherJob : "";
    $MotherDegree  = isset($input->MotherDegree) ? $input->MotherDegree : "";
    $FatherDegree  = isset($input->FatherDegree) ? $input->FatherDegree : "";
    $OtherDegree  = isset($input->OtherDegree) ? $input->OtherDegree : "";
    $MotherBirth  = isset($input->MotherBirth) ? $input->MotherBirth : "";
    $FatherBirth  = isset($input->FatherBirth) ? $input->FatherBirth : "";



    $sql = "insert into member ( Id,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth) values ('$Id','$Did','$Name','$Phone','$Province','$City','$County','$Email','$PostCode','$Address','$MotherJob','$FatherJob','$MotherDegree','$FatherDegree','$OtherDegree','$MotherBirth','$FatherBirth')";

    $result = $conn->query($sql);
    $sql  = "select Id,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";

    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }
  } else if ($Flow == 'deleteMember') {
    $Id = $input->Id;

    $conn->query("delete from baby where Mid='$Id'");
    $conn->query("delete from member where Id='$Id'");

    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
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


    $sql  = "update  member set Name='$Name',Phone='$Phone',Email='$Email',PostCode='$PostCode',Address='$Address'  where Id='$Id'";
    $conn->query($sql);


    $sql  = "selectId,Did,Name,Phone,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();


      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
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
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" =>  $model
      ]
    );
  }
}
