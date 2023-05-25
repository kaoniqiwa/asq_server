<?php

session_start();//必须置于顶部启动
// 创建画布
$image = imagecreatetruecolor ( 100, 30 );
$bgcolor = imagecolorallocate ( $image, 160, 200, 180 );
imagefill ( $image, 0, 0, $bgcolor );

// 生成随机数
// for($i=0;$i<4;$i++){
// $fontsize = 6;
// $fontcolor = imagecolorallocate($image, mt_rand(0, 80), mt_rand(50, 100), mt_rand(50, 120));
// $fontcontent = mt_rand(0, 9);

// $x = $i*20+mt_rand(5, 10);
// $y = mt_rand(5, 10);

// imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
// }


$captch_code = "";//空变量
// 生成字母和数字混合
for($i = 0; $i < 4; $i ++) {
    $fontsize = 6;
    $fontcolor = imagecolorallocate ( $image, mt_rand ( 0, 80 ), mt_rand ( 50, 100 ), mt_rand ( 50, 120 ) );

    $data = 'abcdefghijkmnprstuvwxyABCDEFGHIJKLMNPQRSTUVWXY3456789';//除去容易字母数字混淆的元素
    $fontcontent = substr($data, mt_rand(0, strlen($data)),1);

    $captch_code .= $fontcontent;

    $x = $i * 20 + mt_rand ( 5, 10 );
    $y = mt_rand ( 5, 10 );

    imagestring ( $image, $fontsize, $x, $y, $fontcontent, $fontcolor );
}

    $_SESSION['auto_code'] = $captch_code;//保存在session的auto_code变量中

// 增加干扰点
for($i = 0; $i < 200; $i ++) {
    $pointColor = imagecolorallocate ( $image, mt_rand ( 100, 200 ), mt_rand ( 150, 180 ), mt_rand ( 100, 240 ) );
    imagesetpixel ( $image, mt_rand ( 1, 99 ), mt_rand ( 1, 29 ), $pointColor );
}

// 增加干扰线
for($i = 0; $i < 4; $i ++) {
    $lineColor = imagecolorallocate ( $image, mt_rand ( 0, 200 ), mt_rand ( 50, 180 ), mt_rand ( 80, 240 ) );
    imageline ( $image, mt_rand ( 1, 99 ), mt_rand ( 1, 29 ), mt_rand ( 1, 99 ), mt_rand ( 1, 29 ), $lineColor );
}

header ( "content-type:image/png" );
$backimage =  imagepng ( $image );
imagedestroy ( $image );
