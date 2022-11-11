<?php
require_once "./SignatureHelper.php";
use Aliyun\DySDKLite\SignatureHelper;

include('./utility/tool.php');
include('./utility/mysql.php');

session_start();
$method = strtolower($_SERVER['REQUEST_METHOD']);
$aliyunParams = array(
  "name"=>"《ASQ系统中文版》内部示教账号",
  "accessKeyId"=>"LTAIjBOUDhzisb91",
  "accessKeySecret"=>"Enzsps9cknVvbMCMfRuKymrnHHmhCh",
  "security"=>false,
  "tc1"=>'SMS_48075026',
  "tc2"=>'SMS_257857761',
);

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
    $AsqSe2Left = $input->AsqSe2Left;
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
  } else if ($Flow == 'sendSms') {
    $phone = $input->phone;
    $model = sendSms($phone);
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $model
      ]
    );
  } else if ($Flow == 'sendUrl') {
    $phone = $input->phone;
    $type = $input->type;
    $uid = $input->uid;
    $did = $input->did;
    $un = $input->un;
    $pw = $input->pw;
    $sn = $input->sn;

    $sql  = "select * message where Uid='$Uid' and Did='$Did' and Did='$Did' and Mphone='$phone' and Typeid='$type'";
    $result =  $conn->query($sql);

    if ($conn->affected_rows != 0) {
      //$rs  =  $result->fetch_assoc();
      $sql  = "update message set Status=0 where where Uid='$Uid' and Did='$Did' and Did='$Did' and Mphone='$phone' and Typeid='$type'";
      $conn->query($sql);
    }else{
      $sql = "insert into message ( Id,Uid,Did,Mphone,Status,CreateTime) values ('$Id','$Uid','$Did','$phone',0,'$CreateTime')";
      $conn->query($sql);
    }
    
    $model = sendUrl($phone,$type,$uid,$did,$un,$pw,$sn);
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $model
      ]
    );
  } else if ($Flow == 'yzSms') {
    $code = $input->code;
    $str = '';
    //$model = array();
    if(isset($code)){ 
      session_start(); 
      if(strtolower($code)==strtolower($_SESSION['code'])){ 
        $model['message'] = '正确';
        $model['code'] = 0;
      }else{ 
        $model['message'] = '错误';
        $model['code'] = 1;
      } 
      $model['message'] = '验证码未传值';
      $model['code'] = 1;
    }
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $model
      ]
    );
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
  } else if ($Flow == 'updateLeft') {
    $Id = $input->uid;
    $asqtype = $input->type;
    $UpdateTime  = date('Y-m-d H:i:s', time());

    $sql  = "select ".$asqtype." from company where Id='$Id'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model = $result->fetch_assoc();
    }
    //echo json_encode($model[$asqtype]); 
    if((int)$model[$asqtype] <= 0){
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => false
        ]
      );
      exit;
    }

    $sql  = "update company set ".$asqtype."=".$asqtype."-1,UpdateTime='$UpdateTime' where Id='$Id' and ".$asqtype.">0";
    //echo $sql;
    $result = $conn->query($sql);
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $result
      ]
    );

    
  } else if ($Flow == 'getUuid') {
    $Id = GUID();
    $Uid = $input->Uid;
    //$Did = $input->Did;
    $Uuid = $Id;
    $CreateTime = date('Y-m-d H:i:s', time());
    $Status = 0;
    
    $sql = "insert into qrcode ( Id,Uuid,Status,CreateTime) values ('$Id','$Uuid','$Status','$CreateTime')";
    $result = $conn->query($sql);
    
    $sql  = "select Id,Name,Username,Password from company where Id='$Uid'";
    $result =  $conn->query($sql);
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      $model['Username'] = urlencode(base64_encode($model['Username']));
      $model['Password'] = urlencode(base64_encode($model['Password']));
      $model['Uuid'] = $Uuid;
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => $model
        ]
      );
    }else{
      echo json_encode(
        [
          "FaultCode" => 1,
          'FaultReason' => 'error',
        ]
      );
    }
  } else if ($Flow == 'checkUuid') {
    
    $Uuid = $input->Uuid;
    $EndTime = date('Y-m-d H:i:s', time());

    $sql  = "select Status from qrcode where Uuid='$Uuid'";
    $result = $conn->query($sql);
    //$rs = $conn->field_count();
    if ($conn->affected_rows != 0) {
      $rs = $result->fetch_assoc();
      
      if((int)$rs['Status'] == 0){
       
        $sql = "update qrcode set Status=Status+1,EndTime='$EndTime' where Uuid='$Uuid'";
        //echo $sql;
        $result = $conn->query($sql);
        
        echo json_encode(
          [
            "FaultCode" => 0,
            'FaultReason' => 'OK',
            'Data' => true
          ]
        );
      }else{
        echo json_encode(
          [
            "FaultCode" => 0,
            'FaultReason' => 'OK',
            'Data' => false
          ]
        );
      }
    }

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
    $AsqSe2Left = $input->AsqSe2Left;
    $UpdateTime  = date('Y-m-d H:i:s', time());


    $sql  = "update company set Name='$Name',Username='$Username',Password='$Password',AsqTotal='$AsqTotal',AsqLeft='$AsqLeft',AsqSeTotal='$AsqSeTotal',AsqSeLeft='$AsqSeLeft',AsqSe2Total='$AsqSe2Total',
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


function sendSms($phone) {

  $params = array ();

  // *** 需用户填写部分 ***
  // fixme 必填：是否启用https
  $security = false;

  // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
  $accessKeyId = "LTAIjBOUDhzisb91";
  $accessKeySecret = "Enzsps9cknVvbMCMfRuKymrnHHmhCh";

  $code = rand ( 100000, 999999 );
  $_SESSION['code'] = $code;

  // fixme 必填: 短信接收号码
  $params["PhoneNumbers"] = $phone;

  // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
  $params["SignName"] = "在线筛查";

  // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
  $params["TemplateCode"] = "SMS_48075026";

  // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
  $params['TemplateParam'] = Array (
      "code" => $code,
      "product" => "阿里通信"
  );

  // fixme 可选: 设置发送短信流水号
  //$params['OutId'] = "12345";

  // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
  //$params['SmsUpExtendCode'] = "1234567";


  // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
  if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
      $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
  }

  // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
  $helper = new SignatureHelper();

  // 此处可能会抛出异常，注意catch
  $content = $helper->request(
      $accessKeyId,
      $accessKeySecret,
      "dysmsapi.aliyuncs.com",
      array_merge($params, array(
          "RegionId" => "cn-hangzhou",
          "Action" => "SendSms",
          "Version" => "2017-05-25",
      )),
      $security
  );

  $model = array();

  $model['code'] = $code;
  $model['content'] = $content;

  return $model;
}


function sendUrl($phone,$type,$uid,$did,$un,$pw,$sn) {
  //http://asq.neoballoon.com/app/asq_frontend/#/mlogin?uid=${uid}&did=${did}&username=${un}&password=${pw}&phone=${phone}&type=${type}
  global $aliyunParams;
  $params = array ();

  // *** 需用户填写部分 ***
  // fixme 必填：是否启用https
  $security = $aliyunParams['security'];

  // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
  $accessKeyId = $aliyunParams['accessKeyId'];
  $accessKeySecret = $aliyunParams['accessKeySecret'];

  // fixme 必填: 短信接收号码
  $params["PhoneNumbers"] = $phone;

  // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
  $params["SignName"] = $sn;

  // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
  $params["TemplateCode"] = $aliyunParams['tc2'];

  // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
  $params['TemplateParam'] = Array (
      "typename" => $type,
      "type" => $type,
      "uid" => $uid,
      "did" => $did,
      "un" => $un,
      "pw" => $pw,
      "name" => $aliyunParams['name'],
      "product" => "阿里通信"
  );
  // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
  if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
      $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
  }

  // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
  $helper = new SignatureHelper();

  // 此处可能会抛出异常，注意catch
  $content = $helper->request(
      $accessKeyId,
      $accessKeySecret,
      "dysmsapi.aliyuncs.com",
      array_merge($params, array(
          "RegionId" => "cn-hangzhou",
          "Action" => "SendSms",
          "Version" => "2017-05-25",
      )),
      $security
  );

  $model = array();
  $model['content'] = $content;

  return $model;
}