<?php

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
