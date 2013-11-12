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
$html = file_get_html('something/yidong/yidong.html');



// Find all <td> in <table> which class=hello
// $es = $html->find('table.hello td');

$pattern     = "/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0$/"; //非负浮点数的正则表达式
$pattern1    = "/^[A-Z]+$/"; //大写英文字母字符串正则表达式
$pattern2    = "/[0-9]+\d/"; //匹配正整数，无法匹配个位数
$pattern3    = "/MB|GB/";
$pattern4    = "/<.*?>/";
$pattern5    = "/>\d+</";
$pattern6    = "/([1-9]+\d)|[0]/"; //匹配非负整数
$pattern7    = "/([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0)|([1-9]+\d)/";
$pattern8    = "/(<.*?>)|MB|GB/";
$pattern9    = "/<.*?>|[^0-9]/";//替换非数字
$pattern10="/[\x{4e00}-\x{9fa5}]+/u";//替换中文
$count       = 0;
$i           = 0;
$name        = '上网套餐';
$message     = 0;
$phone_m     = array(
    0.25,
    0.19
);
$message=0; $video=0;$video_m=0;
$flow_k      = 0.0005;
$message_m   = 0.1;
$arr         = array(//这是需要获取信息的dom树节点记号，每三个一组
    58,
    59,
    61,
    66,
    67,
    68,
    72,
    73,
    74,
    77,
    78,
    79,
    82,
    83,
    84,
    87,
    88,
    89,
    92,
    93,
    94,
    97,
    98,
    99,
    102,
    103,
    104
);
$info=array(
    'monthly_fee'=>'',
    'total_phone'=>'',
    'flow'=>''
    );
$cc=-1;
//1281行话费套餐表

foreach ($html->find('table') as $table) {
    foreach ($table->find('tr') as $tr) {
        foreach ($tr->find('td') as $td) {
            
            $i++;
            $count++;

            foreach ($arr as $key) {
                if ($count == $key) {
                    // var_dump("$td");
                    $cc++;
                    $str= preg_replace($pattern9, "", "$td");
                 switch ($cc%3) {
                     case 0:
                         $info['monthly_fee']=$str;
                         break;
                    case 1:
                         $info['total_phone']=$str;
                         break;
                    case 2:
                         $info['flow']=$str;
                         var_dump($info);
                         if($cc<=3)
                        $sql = sprintf("insert into yidong values('%s','%s','%s', $message, $video,$phone_m[0],$video_m,$flow_k,$message_m)",$info['monthly_fee'], $info['total_phone'], $info['flow']);
                         else
                        $sql = sprintf("insert into yidong values('%s','%s','%s', $message, $video,$phone_m[1],$video_m,$flow_k,$message_m)",$info['monthly_fee'], $info['total_phone'], $info['flow']);
                         //加入数据库
                        mysql_query($sql, $con);
                         break;
                 }
                 // var_dump($cc);
                }
            }
        }
    }
}





mysql_close($con);

?>