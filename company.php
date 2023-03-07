<?php
require_once "./SignatureHelper.php";
use Aliyun\DySDKLite\SignatureHelper;

include('./utility/tool.php');
include('./utility/mysql.php');

session_start();
$method = strtolower($_SERVER['REQUEST_METHOD']);
$aliyunParams = array(
  "accessKeyId"=>"LTAIjBOUDhzisb91",
  "accessKeySecret"=>"Enzsps9cknVvbMCMfRuKymrnHHmhCh",
  "security"=>false,
  "tc1"=>'SMS_48075026',
  "tc2"=>'SMS_270835012',
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


    $sql = "select Seq,Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company  where Name like '%$Name%'  or Username like '%$Name%'";


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

    $sql  = "select Seq,Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Id='$Id'";
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
    $typeid = urldecode($input->type);
    $uid = $input->uid;
    $did = $input->did;
    $un = $input->un;
    $pw = $input->pw;
    $sn = $input->sn;
    $Am = $input->Am;
    $At = $input->At;
    $name = $input->name;
    $Id = GUID();

    $CreateTime = date('Y-m-d H:i:s', time());
    $UpdateTime = date('Y-m-d H:i:s', time());

    /* $sql  = "select * from message where Uid='$uid' and Did='$did' and Mphone='$phone' and Typeid='$typeid'";
    $result =  $conn->query($sql);

    if ($conn->affected_rows != 0) {
      $sql  = "update message set Status=0,UpdateTime='$UpdateTime' where Uid='$uid' and Did='$did' and Mphone='$phone' and Typeid='$typeid'";
      $conn->query($sql);
    }else{
      $sql = "insert into message ( Id,Uid,Did,Typeid,Mphone,Status,CreateTime) values ('$Id','$uid','$did','$typeid','$phone',0,'$CreateTime')";
      $conn->query($sql);
    } */

    $sql = "insert into message ( Id,Uid,Did,Typeid,Mphone,Status,CreateTime) values ('$Id','$uid','$did','$typeid','$phone',0,'$CreateTime')";
    $conn->query($sql);

    $seq = mysqli_insert_id($conn);
    
    $model = sendUrl($phone,$type,$uid,$did,$un,$pw,$sn,$Am,$At,$name,$seq);
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $model
      ]
    );
  } else if ($Flow == 'getStatus') {
    $phone = $input->phone;
    $type = $input->type;
    $uid = $input->uid;
    $did = $input->did;
    $seq = $input->seq;

    $sql  = "select * from message where Seq='$seq'";
    $result =  $conn->query($sql);

    //echo $sql;
    $Status = true;
    if ($conn->affected_rows != 0) {
      $model  =  $result->fetch_assoc();
      if($model['Status'] == 0){
        $Status = true;
      }else{
        $Status = false;
      }
    }
    
    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        'Data' => $Status
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
    $Ids = $input->Ids;

    for($i=0;$i<count($Ids);$i++){
      //$sql = "select * from company where Id='$Id'";
      $conn->query("delete from doctor where cid='$Ids[$i]'");
      $conn->query("delete from company where Id='$Ids[$i]'");
    }

    echo json_encode(
      [
        "FaultCode" => 0,
        'FaultReason' => 'OK',
        "Data" => $Ids
      ]
    );
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

  } else if ($Flow == 'checkLeft') {
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
    }else{
      echo json_encode(
        [
          "FaultCode" => 0,
          'FaultReason' => 'OK',
          'Data' => true
        ]
      );
    }
    
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
       
        /* $sql = "update qrcode set Status=Status+1,EndTime='$EndTime' where Uuid='$Uuid'";
        $result = $conn->query($sql); */
        
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
  } else if ($Flow == 'getUserBySeq') {
    $Seq = $input->Seq;

    $sql  = "select Seq,Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Seq='$Seq'";
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
  $sql = "select Seq,Id,Name,Username,Password,AsqTotal,AsqLeft,AsqSeTotal,AsqSeLeft,AsqSe2Total,AsqSe2Left,CreateTime,UpdateTime from company where Id='$Id'";

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


function sendUrl($phone,$type,$uid,$did,$un,$pw,$sn,$Am,$At,$name,$seq) {
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
      "typename" => urldecode($type),
      "type" => $type,
      "uid" => $uid,
      "did" => $did,
      "un" => $un,
      "pw" => $pw,
      "name" => $name,
      "Am" => $Am,
      "At" => $At,
      "phone" => $phone,
      "seq" => $seq,
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

function sendBatchUrl($phones,$type,$uid,$did,$un,$pw,$sn,$Am,$At,$name) {

  global $aliyunParams;
  $params = array ();

  // *** 需用户填写部分 ***
  // fixme 必填：是否启用https
  $security = $aliyunParams['security'];

  // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
  $accessKeyId = $aliyunParams['accessKeyId'];
  $accessKeySecret = $aliyunParams['accessKeySecret'];

  // fixme 必填: 待发送手机号。支持JSON格式的批量调用，批量上限为100个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
  $params["PhoneNumberJson"] = $phones;

  // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
  $params["TemplateCode"] = $aliyunParams['tc2'];

  // fixme 必填: 短信签名，支持不同的号码发送不同的短信签名，每个签名都应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
  /* $params["SignNameJson"] = array(
      "云通信",
      "云通信2",
  ); */

  

  // fixme 必填: 模板中的变量替换JSON串,如模板内容为"亲爱的${name},您的验证码为${code}"时,此处的值为
  // 友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
  /* $params["TemplateParamJson"] = array(
      array(
          "name" => "Tom",
          "code" => "123",
      ),
      array(
          "name" => "Jack",
          "code" => "456",
      ),
  ); */

  $params['TemplateParam'] = Array (
    "typename" => $type,
    "type" => $type,
    "uid" => $uid,
    "did" => $did,
    "un" => $un,
    "pw" => $pw,
    "name" => $name,
    "Am" => $Am,
    "At" => $At,
    "phone" => '',
    "product" => "阿里通信"
);

  $params["SignNameJson"] = array();
  $params["TemplateParamJson"] = $params['TemplateParam'];

  for($i=0;$i<count($phones);$i++){
    array_push($params["SignNameJson"], $sn);
    //array_push($params["TemplateParamJson"], $sn);
    $params["TemplateParamJson"]['phone'] = $phones[$i];

  }

  // todo 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
  // $params["SmsUpExtendCodeJson"] = json_encode(array("90997","90998"));


  // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
  $params["TemplateParamJson"]  = json_encode($params["TemplateParamJson"], JSON_UNESCAPED_UNICODE);
  $params["SignNameJson"] = json_encode($params["SignNameJson"], JSON_UNESCAPED_UNICODE);
  $params["PhoneNumberJson"] = json_encode($params["PhoneNumberJson"], JSON_UNESCAPED_UNICODE);

  if(!empty($params["SmsUpExtendCodeJson"]) && is_array($params["SmsUpExtendCodeJson"])) {
      $params["SmsUpExtendCodeJson"] = json_encode($params["SmsUpExtendCodeJson"], JSON_UNESCAPED_UNICODE);
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
          "Action" => "SendBatchSms",
          "Version" => "2017-05-25",
      )),
      $security
  );

  $model = array();
  $model['content'] = $content;

  return $model;
}