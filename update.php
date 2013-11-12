<html>
<head>
<meta http-equiv="refresh" content="36000">
<!-- 36000秒（10小时）刷新一次页面 -->
</head>
<?php
header("content-type: text/html; charset=utf-8");

$url=array(
    'a_plan.php',
    'b_plan.php',
    'c_plan.php',
    'yidong.php',
    'tele.php'
    );

for($i=0;$i<count($url);$i++){
require_once("$url[$i]");
}

//还有一种方法，将以上的php封装成一个函数，直接执行函数也可以
?>
</html>