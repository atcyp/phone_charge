<?php
date_default_timezone_set("Asia/Harbin");
header("content-type: text/html; charset=utf-8");
set_time_limit(10000); //允许处理时间
//连接数据库
$con = mysql_connect('localhost', 'root', '') or die("connect failed" . mysql_error());
mysql_select_db("phone_charge") or die(mysql_error());
mysql_query("set names 'utf8'");

include "simple_html_dom.php";
// Create DOM from URL or file
$html = file_get_html('something/tele/tele.html');



// Find all <td> in <table> which class=hello
// $es = $html->find('table.hello td');

$pattern     = "/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0$/"; //非负浮点数的正则表达式
$pattern1    = "/^[A-Z]+$/"; //大写英文字母字符串正则表达式
$pattern2    = "/[0-9]+\d/"; //匹配正整数，无法匹配个位数
$pattern3    = "/MB|GB|M|G/";
$pattern4    = "/<.*?>/";
$pattern5    = "/>\d+</";
$pattern6    = "/([1-9]+\d)|[0]/"; //匹配非负整数
$pattern7    = "/([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0)|([1-9]+\d)/";
$pattern8    = "/(<.*?>)|MB|GB|M|G/";
$pattern9    = "/<.*?>|[^0-9]/";//替换非数字
$pattern10="/<.*?>|[\x{4e00}-\x{9fa5}]+/u";//替换中文
$count       = 0;
$i           = 0;
$name        = '上网套餐';
$message     = array(
    30,60,180
    );
$phone_m     = array(
    0.2,
    0.15
);
$wifi=array(
    30,60,120
    );
$message=array(
    30,60,180
    ); 
$video=0;$video_m=0; 
$flow_k      = 0.0003;
$message_m   = 0.1;
$arr         = array(//这是需要获取信息的dom树节点记号，每三个一组
13,14,15,23,24,25,27,28,29,32,33,34,40,41,42,44,45,46,48,49,50,55,56,57,59,60,61,63,64,65,67,68,69
);
$info=array(
    'monthly_fee'=>'',
    'flow'=>'',
    'total_phone'=>''
    );
$cc=-1;

foreach ($html->find('table') as $table) {
    foreach ($table->find('tr') as $tr) {
        foreach ($tr->find('td') as $td) {
            
            $i++;
            $count++;

                 foreach ($arr as $key) {
                if ($count == $key) {
                    // var_dump("$td");
                     $cc++;
                    $str= preg_replace($pattern10, "", "$td");
                 switch ($cc%3) {
                     case 0:
                         $info['monthly_fee']=$str;
                         break;
                    case 1:
                     if (preg_match($pattern3, $td, $matches) == 1) {
                        if ($matches[0] == 'GB'||$matches[0] == 'G') {                            
                            $info['flow']= preg_replace($pattern8, "", $str) * 1024; 
                        }                      
                        else {                         
                            $info['flow']= preg_replace($pattern8, "", $str);                        
                        }               
                    }
                         break;
                    case 2:
                         $info['total_phone']=$str;
                         var_dump($info);
                       if($cc<=6)
                        $sql = sprintf("insert into tele values('%s','%s','%s', $message[0], $video,$phone_m[0],$video_m,$flow_k,$message_m,$wifi[0])",$info['monthly_fee'], $info['total_phone'], $info['flow']);
                        else  if($cc>=7&&$cc<=9)
                            $sql = sprintf("insert into tele values('%s','%s','%s', $message[0], $video,$phone_m[1],$video_m,$flow_k,$message_m,$wifi[0])",$info['monthly_fee'], $info['total_phone'], $info['flow']);
                        else  if($cc>=10&&$cc<=18)
                            $sql = sprintf("insert into tele values('%s','%s','%s', $message[1], $video,$phone_m[1],$video_m,$flow_k,$message_m,$wifi[1])",$info['monthly_fee'], $info['total_phone'], $info['flow']);
                        else
                            $sql = sprintf("insert into tele values('%s','%s','%s', $message[2], $video,$phone_m[1],$video_m,$flow_k,$message_m,$wifi[2])",$info['monthly_fee'], $info['total_phone'], $info['flow']);
                         //加入数据库
                        mysql_query($sql, $con);
                         break;
                }
            }                      
        }
    }
}
}

mysql_close($con);

?>