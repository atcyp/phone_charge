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
$html = file_get_html('something/union/b.html');



// Find all <td> in <table> which class=hello
// $es = $html->find('table.hello td');

$pattern     = "/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0$/"; //非负浮点数的正则表达式
$pattern1    = "/^[A-Z]+$/"; //大写英文字母字符串正则表达式
$pattern2    = "/[0-9]+\d/"; //匹配正整数，"/[1-9]+\d/"无法匹配个位数,也无法匹配200之类的，所以可以全部改为用preg_replace()函数实现，或者修改正则。改为0-9可能会出bug
$pattern3    = "/MB|GB/";
$pattern4    = "/<.*?>/";
$pattern5    = "/>\d+</";
$pattern6    = "/([1-9]+\d)|[0]/"; //匹配非负整数
$pattern7    = "/([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0)|([1-9]+\d)/";
$pattern8    = "/(<.*?>)|MB|GB/";
$count       = 0;
$i           = 0;
$monthly_fee = array();
$total_phone = array();
$message     = 0;
$phone_m     = array();
$video_m     = 0.60;
$flow_k      =0.0003 ;
$message_m   = 0.1;

foreach ($html->find('table') as $table) {
    foreach ($table->find('tr') as $tr) {
        foreach ($tr->find('td') as $td) {
            
            // echo($td.'<br>');
            //跨列值为空即是默认为1列
            if ($td->colspan == null)
                $td->colspan = 1;
      
            // 月租    
            if ($count > 0 && $count < 7) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str =  "$td"; //object转为string  
                    // var_dump($str);                                                                                
                    
                        $monthly_fee[$i] = preg_replace($pattern4, "", $str);        
                    
                    var_dump($monthly_fee[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //国内通话时间
            else if ($count > 8 && $count < 15) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str =  "$td";
                    if (preg_match($pattern2, $str, $matches) == 1) {
                        $total_phone[$i] = $matches[0]; //正则匹配提取数字                          
                    }
                    var_dump($total_phone[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //流量
            else if ($count > 15 && $count < 22) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str =  "$td";
                    // var_dump($str);
                    if (preg_match($pattern3, $td, $matches) == 1) {
                        if ($matches[0] == 'GB') {                           
                            $flow[$i] = preg_replace($pattern8, "", $str) * 1024;                               
                            var_dump($flow[$i]);
                        }        
                        else {
                            $flow[$i] = preg_replace($pattern8, "", $str);
                            var_dump($flow[$i]);                        
                        }
                        $i += 1;
                        $count += 1;
                    }
                }
            }
            //国内通话单价：元/分钟
            else if ($count > 25 && $count < 32) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str =  "$td";
                    if (preg_match($pattern, $str, $matches) == 1) {
                        $phone_m[$i] =  "$matches[0]";
                    }
                    var_dump($phone_m[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //可视电话通话时间
            else if ($count > 53 && $count < 60) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str       =  "$td";
                    $video[$i] = preg_replace($pattern8, "", $str);
                    var_dump($video[$i]);
                    $i += 1;
                    $count += 1;
                }
            } else {
                $i = 0;
                $count += 1;
            }
            
        }
    }
    break; //这样才是刚好2个表,不理解
}
// $e = $html->find('tr', 0)->find('td', 0);
// echo $e;

for ($i = 0; $i < 6; $i++) {
    $sql = "insert into b_plan values($monthly_fee[$i], $total_phone[$i], $flow[$i], $message, $video[$i],$phone_m[$i],$video_m,$flow_k,$message_m)";
    //加入数据库
    mysql_query($sql, $con);
}

mysql_close($con);

?>