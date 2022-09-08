<?php
class Mysql
{
  private $db_host; //数据库主机
  private $db_user; //数据库用户名
  private $db_pwd; //数据库用户名密码
  private $db_database; //数据库名
  private $conn; //数据库连接标识;
  private $result; //执行query命令的结果资源标识
  private $sql; //sql执行语句
  private $row; //返回的条目数
  private $coding; //数据库编码，GBK,UTF8,gb2312
  private $bulletin = true; //是否开启错误记录
  private $show_error = true; //测试阶段，显示所有错误,具有安全隐患,默认关闭
  private $is_error = true; //发现错误是否立即终止,默认true,建议不启用，因为当有问题时用户什么也看不到是很苦恼

  function __construct($db_host, $db_user, $db_pwd, $db_database, $coding)
  {
    $this->db_host = $db_host;
    $this->db_user = $db_user;
    $this->db_pwd = $db_pwd;
    $this->db_database = $db_database;
    $this->coding = $coding;
    // var_dump($this);
    $this->connect();
  }
}
