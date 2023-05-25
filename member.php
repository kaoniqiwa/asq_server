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

  if ($Flow == 'getMember') {
    
    $Phone = isset($input->Phone) ? $input->Phone : '';
    $model = [];
    
    if($Phone != ''){
      $sql = "select * from member where Phone = '$Phone' ";
      $result = $conn->query($sql);
     
      if ($conn->affected_rows != 0) {
        //$model  = $result->fetch_assoc();
        while ($rs = $result->fetch_assoc()) {
          array_push($model, $rs);
        }   
      }
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          "Data" =>  $model
        ]
      );
    }else{
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          "Data" =>  $model
        ]
      );
    }

    


  }else if ($Flow == 'listMember') {
    $PageSize = $input->PageSize;
    $PageIndex = $input->PageIndex;
    $Name = isset($input->Name) ? $input->Name : "";
    //$Dids = isset($input->Dids) ? $input->Dids : [];
    $Ids = isset($input->Ids) ? $input->Ids : [];
    $Phones = isset($input->Phones) ? $input->Phones : [];

    $Start = ($PageIndex - 1) * $PageSize;

    $sql = "select * from member where Name like '%$Name%' or Phone like '%$Name%' ";
    $result = $conn->query($sql);

    
    $tmp = array();

    if ($conn->affected_rows != 0) {
      $RecordCount = $conn->affected_rows;
      while ($rs = $result->fetch_assoc()) {
        array_push($tmp, $rs);
      }
    }

    $tmp2 = $tmp;

    /* if (count($Dids) == 0) {
      $tmp2 = $tmp;
    } else {
      $tmp2 = [];
      for ($i = 0; $i < count($tmp); $i++) {
        for ($j = 0; $j < count($Dids); $j++) {
          if ($tmp[$i]['Did'] == $Dids[$j]) {
            array_push($tmp2, $tmp[$i]);
            break;
          }
        }
      }
    } */

    if (count($Ids) == 0) {
      $tmp3 = $tmp2;
    } else {
      $tmp3 = [];
      for ($i = 0; $i < count($tmp2); $i++) {
        for ($j = 0; $j < count($Ids); $j++) {
          if ($tmp2[$i]['Id'] == $Ids[$j]) {
            array_push($tmp3, $tmp2[$i]);
            break;
          }
        }
      }
    }

    if (count($Phones) == 0) {
      $tmp4 = $tmp3;
    } else {
      $tmp4 = [];
      for ($i = 0; $i < count($tmp3); $i++) {
        for ($j = 0; $j < count($Phones); $j++) {
          if ($tmp2[$i]['Phone'] == $Phones[$j]) {
            array_push($tmp4, $tmp3[$i]);
            break;
          }
        }
      }
    }




    $TotalRecortCount = count($tmp4);
    $Data =  array_slice($tmp4, $Start, $PageSize);
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
  } else if ($Flow == 'addMember') {
    $Id = GUID();
    $Did = $input->Did;
    $Name = $input->Name;
    $Phone = $input->Phone;
    $Relation = $input->Relation;


    $Province  = isset($input->Province) ? $input->Province : "";
    $City  = isset($input->City) ? $input->City : "";
    $County  = isset($input->County) ? $input->County : "";
    $Email  = isset($input->Email) ? $input->Email : "";
    $PostCode  = isset($input->PostCode) ? $input->PostCode : "";
    $Address  = isset($input->Address) ? $input->Address : "";
    $IsHelp  = isset($input->IsHelp) ? $input->IsHelp : "";
    $HelpInfo  = isset($input->HelpInfo) ? $input->HelpInfo : "";
    $MotherJob  = isset($input->MotherJob) ? $input->MotherJob : "";
    $FatherJob  = isset($input->FatherJob) ? $input->FatherJob : "";
    $MotherDegree  = isset($input->MotherDegree) ? $input->MotherDegree : "";
    $FatherDegree  = isset($input->FatherDegree) ? $input->FatherDegree : "";
    $OtherDegree  = isset($input->OtherDegree) ? $input->OtherDegree : "";
    $MotherBirth  = isset($input->MotherBirth) ? $input->MotherBirth : "";
    $FatherBirth  = isset($input->FatherBirth) ? $input->FatherBirth : "";
    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime  = date('Y-m-d H:i:s', time());

    $sql = "insert into member ( Id,Did,Name,Phone,Relation,Province,City,County,Email,IsHelp,HelpInfo,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime) values ('$Id','$Did','$Name','$Phone','$Relation','$Province','$City','$County','$Email','$IsHelp','$HelpInfo','$PostCode','$Address','$MotherJob','$FatherJob','$MotherDegree','$FatherDegree','$OtherDegree','$MotherBirth','$FatherBirth','$CreateTime','$UpdateTime')";

    // var_dump($sql);

    $result = $conn->query($sql);
    $sql  = "select Id,Did,Name,Phone,Province,Relation,City,County,Email,IsHelp,HelpInfo,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";

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
    $sql  = "select Id,Did,Name,Phone,Relation,Province,City,County,Email,IsHelp,HelpInfo,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();

      $conn->query("delete from baby where Mid='$Id'");
      $conn->query("delete from member where Id='$Id'");

      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }
  } else if ($Flow == 'updateMember') {


    $Id = $input->Id;
    $Name = $input->Name;
    $Relation = $input->Relation;
    $Province  = isset($input->Province) ? $input->Province : "";
    $City  = isset($input->City) ? $input->City : "";
    $County  = isset($input->County) ? $input->County : "";
    $Email  = isset($input->Email) ? $input->Email : "";
    $IsHelp  = isset($input->IsHelp) ? $input->IsHelp : "";
    $HelpInfo  = isset($input->HelpInfo) ? $input->HelpInfo : "";
    $PostCode  = isset($input->PostCode) ? $input->PostCode : "";
    $Address  = isset($input->Address) ? $input->Address : "";
    $MotherJob  = isset($input->MotherJob) ? $input->MotherJob : "";
    $FatherJob  = isset($input->FatherJob) ? $input->FatherJob : "";
    $MotherDegree  = isset($input->MotherDegree) ? $input->MotherDegree : "";
    $FatherDegree  = isset($input->FatherDegree) ? $input->FatherDegree : "";
    $OtherDegree  = isset($input->OtherDegree) ? $input->OtherDegree : "";
    $MotherBirth  = isset($input->MotherBirth) ? $input->MotherBirth : "";
    $FatherBirth  = isset($input->FatherBirth) ? $input->FatherBirth : "";
    $UpdateTime  = date('Y-m-d H:i:s', time());

    $sql  = "update  member set Name='$Name',Relation='$Relation',Province='$Province',City='$City',County='$County',IsHelp='$IsHelp',HelpInfo='$HelpInfo',PostCode='$PostCode',Address='$Address',MotherJob='$MotherJob',FatherJob='$FatherJob',MotherDegree='$MotherDegree',FatherDegree='$FatherDegree',OtherDegree='$OtherDegree',MotherBirth='$MotherBirth',FatherBirth='$FatherBirth',UpdateTime='$UpdateTime' where Id='$Id'";

    $conn->query($sql);


    $sql  = "select Id,Did,Name,Phone,Relation,Province,City,County,Email,PostCode,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime from member where Id='$Id'";
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
  } else if ($Flow == 'getByPhone') {
    $Phone = $input->Phone;
    var_dump($Phone);
  }
} else if ($method == 'get') {
  $Id = $_GET['Id'];

  $sql  = "select Id,Did,Name,Phone,Relation,Province,City,County,Email,PostCode,IsHelp,HelpInfo,Address,MotherJob,FatherJob,MotherDegree,FatherDegree,OtherDegree,MotherBirth,FatherBirth,CreateTime,UpdateTime  from member where Id='$Id'";

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
