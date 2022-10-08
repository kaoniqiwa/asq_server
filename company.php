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
  if ($Flow == 'listCompany') {
    $PageSize = $input->PageSize;
    $PageIndex = $input->PageIndex;
    $Name = isset($input->Name) ? $input->Name : "";
    $Ids = isset($input->Ids) ? $input->Ids : [];

    $Start = ($PageIndex - 1) * $PageSize;


    $sql = "select Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company  where Name like '%$Name%'  or Username like '%$Name%'";


    $result = $conn->query($sql);

    $tmp = array();

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($tmp, $rs);
      }
    }
    if (count($Ids) == 0) {
      $tmp2 = $tmp;
    } else {
      $tmp2 = [];
      for ($i = 0; $i < count($tmp); $i++) {
        for ($j = 0; $j < count($Ids); $j++) {
          if ($tmp[$i]['Id'] == $Ids[$j]) {
            array_push($tmp2, $tmp[$i]);
            break;
          }
        }
      }
    }


    $TotalRecortCount = count($tmp2);


    $Data =  array_slice($tmp2, $Start, $PageSize);

    $PageCount  = ceil($TotalRecortCount / $PageSize);
    $RecordCount = count($Data);





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
  } else if ($Flow == 'addCompany') {
    $Id = GUID();
    $Name = $input->Name;
    $Username = $input->Username;
    $Password = $input->Password;
    $AsqTotal = $input->AsqTotal;
    $AsqLeft = $input->AsqLeft;
    $AsqSeTotal = $input->AsqSeTotal;
    $AsqSeLeft = $input->AsqSeLeft;
    $AsqSe2Total = $input->AsqSe2Total;
    $AsqSe2Left = $input->AsqSe2Total;
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());

    $sql = "insert into company ( Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime) values ('$Id','$Name','$Username','$Password','$AsqTotal','$AsqLeft','$AsqSeTotal','$AsqSeLeft','$AsqSe2Total','$AsqSe2Left','$CreateTime','$UpdateTime')";

    $result = $conn->query($sql);

    $sql  = "select Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Id='$Id'";
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
  } else if ($Flow == 'deleteCompany') {
    // $Id = $input->Id;

    // $sql = "select Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Id='$Id'";

    // $model = null;

    // $result = $conn->query($sql);

    // if ($conn->affected_rows != 0) {
    //   $model = $result->fetch_assoc();

    //   $conn->query("delete from doctor where cid='$Id'");
    //   $conn->query("delete from company where Id='$Id'");
    // }
    // echo json_encode(
    //   [
    //     "FaultCode" => 0,
    //     'FaultReason' => 'OK',
    //     "Data" => $model
    //   ]
    // );
  } else if ($Flow == 'editCompany') {
    $Id = $input->Id;
    $Name = $input->Name;
    $Username = $input->Username;
    $Password = $input->Password;
    $AsqTotal = $input->AsqTotal;
    $AsqLeft = $input->AsqLeft;
    $AsqSeTotal = $input->AsqSeTotal;
    $AsqSeLeft = $input->AsqSeLeft;
    $AsqSe2Total = $input->AsqSe2Total;
    $AsqSe2Left = $input->AsqSe2Total;
    $UpdateTime  = date('Y-m-d H:i:s', time());


    $sql  = "update  company set Name='$Name',Username='$Username',Password='$Password',AsqTotal='$AsqTotal',AsqLeft='$AsqLeft',AsqSeTotal='$AsqSeTotal',AsqSeLeft='$AsqSeLeft',AsqSe2Total='$AsqSe2Total',
    AsqSe2Left='$AsqSe2Left',UpdateTime='$UpdateTime' where Id='$Id'";
    $conn->query($sql);


    $sql  = "select Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Id='$Id'";
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
  } else if ($Flow == 'exportCompany') {
    $beginTime = getTime($input->beginTime);
    $endTime = getTime($input->endTime);

    $sql  = "select Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where CreateTime between '$beginTime' and '$endTime'";

    $result = $conn->query($sql);
    $Data = [];

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($Data, $rs);
      }
    }


    foreach ($Data as &$value) {
      $Id = $value['Id'];
      $doctors = getDoctor($Id);
      $value['doctors'] = $doctors;
    }

    echo json_encode(
      array(
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" => $Data
      )
    );
  }
} else if ($method == 'get') {
  $Id = $_GET['Id'];
  $sql = "select Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Id='$Id'";

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
