<?php
date_default_timezone_set("Asia/Shanghai");

function GUID()
{
  if (function_exists('com_create_guid') === true) {
    return trim(com_create_guid(), '{}');
  }

  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function string_make_guid()
{
  // 1、去掉中间的“-”，长度有36变为32
  // 2、字母由“大写”改为“小写”
  if (function_exists('com_create_guid') === true) {
    return strtolower(str_replace('-', '', trim(com_create_guid(), '{}')));
  }

  return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


function getTime($time_str)
{
  $time = strtotime($time_str);
  $date = date_create();

  date_timestamp_set($date, $time);
  return  date_format($date, "Y-m-d H:i:s");
}



function geturl($url)
{
  $headerArray = array("Content-type:application/json;", "Accept:application/json");
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
  $output = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($output, true);
  return $output;
}


function posturl($url, $data)
{
  $data  = json_encode($data);
  $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($curl);
  curl_close($curl);
  return json_decode($output, true);
}


function puturl($url, $data)
{
  $data = json_encode($data);
  $ch = curl_init(); //初始化CURL句柄 
  curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //设置提交的字符串
  $output = curl_exec($ch);
  curl_close($ch);
  return json_decode($output, true);
}

function delurl($url, $data)
{
  $data  = json_encode($data);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $output = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($output, true);
}

function patchurl($url, $data)
{
  $data  = json_encode($data);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);     //20170611修改接口，用/id的方式传递，直接写在url中了
  $output = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($output);
  return $output;
}
