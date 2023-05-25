<?php 
    if(isset($_REQUEST['auto_code'])){
        session_start();

        if(strtolower($_REQUEST['auto_code']) == strtolower($_SESSION['auto_code'])){
            echo '输入的验证码正确！';
        }else{
            echo '验证码输入错误，请重新输入！';
        }
        exit();
    }


?>


<html>
<head>
<meta http-equiv="Content-Type" content="tezt/html;charset=utf-8">
<title>验证码校验</title>

</head>
<body>
    <form method="post" action="./form.php">
    <p>验证码图片：
        <img id="captcha_img" alt="显示失败！！！" src="./captcha.php" width="100" height="30">
        <a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='./captcha.php?r='+Math.random()">换一张</a>
    </p>
    <p>请输入验证码信息：<input type="text" name="auto_code" value=""></p>
    <p><input type="submit" value="提交"></p>
    </form>
</body>
</html>