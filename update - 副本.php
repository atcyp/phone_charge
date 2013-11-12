<html>
<head>
<meta http-equiv="refresh" content="36000">
<!-- 36000秒（10小时）刷新一次页面 -->
</head>
<?php
header("content-type: text/html; charset=utf-8");

//初始化
$ch=curl_init();
$url=array(
    'localhost/phone_charge/a_plan.php',
    'localhost/phone_charge/b_plan.php',
    'localhost/phone_charge/c_plan.php',
    'localhost/phone_charge/yidong.php',
    'localhost/phone_charge/tele.php'
    );

for($i=0;$i<count($url);$i++){
//设置选项，包括URL
curl_setopt($ch, CURLOPT_URL, $url[$i]);

//执行并获取HTML文档内容
$output = curl_exec($ch);

//打印获得的数据
// var_dump($output);
}
//释放curl句柄
curl_close($ch);

//还有一种方法，将以上的php封装成一个函数，直接执行函数也可以
?>
</html>