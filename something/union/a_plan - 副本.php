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
$html = file_get_html('a.html');



// Find all <td> in <table> which class=hello
// $es = $html->find('table.hello td');

$pattern     = "/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0$/"; //非负浮点数的正则表达式
$pattern1    = "/^[A-Z]+$/"; //大写英文字母字符串正则表达式
$pattern2    = "/[1-9]+\d/"; //匹配正整数，无法匹配个位数
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
$message     = array();
$phone_m     = array();

// $str2=iconv("UTF-8","GBK",'国内流量');


foreach ($html->find('table') as $table) {
    foreach ($html->find('tr') as $tr) {
        foreach ($tr->find('td') as $td) {
            
            //跨列值为空即是默认为1列
            if ($td->colspan == null)
                $td->colspan = 1;
            $str = "" . "$td";
            echo($str);
            if(preg_replace($pattern8, "", $str)=='国内流量')  
                echo("11111111111".'<br>');
            //中文string不能判断相等？代码是utf-8，网页是GBK2312编码，转为utf-8一样死翘翘，无力了:原因$td只是object输出的结果，类型是object，fuck
            
            //月租    
            if ($count > 0 && $count < 12) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str = "" . "$td"; //object转为string                                                                                  
                    if (preg_match($pattern2, $str, $matches) == 1) {
                        $monthly_fee[$i] = $matches[0]; //正则匹配提取数字                          
                    }
                    // var_dump($monthly_fee[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //国内通话时间
            else if ($count > 13 && $count < 25) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str = "" . "$td";
                    if (preg_match($pattern2, $str, $matches) == 1) {
                        $total_phone[$i] = $matches[0]; //正则匹配提取数字                          
                    }
                    // var_dump($total_phone[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //流量
            else if ($count > 25 && $count < 37) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    /*mixed preg_replace ( mixed pattern, mixed replacement, mixed subject [, int limit] )
                    在 subject 中搜索 pattern 模式的匹配项并替换为 replacement。如果指定了 limit，则仅替换 limit 个匹配，如果省略 limit 或者其值为 -1，则所有的匹配项都会被替换。*/
                    // $flow[$i] = preg_replace("/[A-Z]/","",$td);//将里面的大写英文字母替换为空串
                    
                    /*int preg_match( string pattern, string subject [, array matches [, int flags]] )
                    在 subject 字符串中搜索与pattern给出的正则表达式相匹配的内容。
                    如果提供了 matches，则其会被搜索的结果所填充。$matches[0] 将包含与整个模式匹配的文本，$matches[1] 将包含与第一个捕获的括号中的子模式所匹配的文本，以此类推。*/
                    $str = "" . "$td";
                    var_dump($str);
                    if (preg_match($pattern3, $td, $matches) == 1) {
                        if ($matches[0] == 'GB') {
                            
                            $flow[$i] = preg_replace($pattern8, "", $str) * 1024; //取消无用符号，存入数组因为特么的这个有浮点数和个位整数，无法匹配                                 
                            // var_dump($flow[$i]);
                        }
                        
                        else {
                            
                            $flow[$i] = preg_replace($pattern8, "", $str);
                            // var_dump($flow[$i]);                        
                        }
                        $i += 1;
                        $count += 1;
                    }
                }
            }
            //短信
            else if ($count > 37 && $count < 49) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str = "" . "$td";
                    // var_dump($str);
                    if (preg_match($pattern6, $str, $matches) == 1) {
                        $message[$i] = $matches[0]; //正则匹配提取数字                          
                    }
                    // echo(preg_replace($pattern4,"",$str).'<br>');//用空串替换掉<>里面的内筒，非贪婪匹配
                    // var_dump($message[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //国内通话单价：元/分钟
            else if ($count > 52 && $count < 63) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str = "" . "$td";
                    if (preg_match($pattern, $str, $matches) == 1) {
                        $phone_m[$i] = "" . "$matches[0]";
                    }
                    // var_dump($phone_m[$i]);
                    $i += 1;
                    $count += 1;
                }
            }
            //可视电话通话时间
            else if ($count > 95 && $count < 107) {
                for ($c = 0; $c < $td->colspan; $c++) {
                    $str       = "" . "$td";
                    $video[$i] = preg_replace($pattern8, "", $str);
                    // var_dump($video[$i]);
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

// $sql="insert into a_plan values($monthly_fee[0],$total_phone[0],$flow[0],$message[0],$video[0]) ";
var_dump($monthly_fee[0]);
var_dump($total_phone[0]);
var_dump($flow[0]);
var_dump($message[0]);
var_dump($video[0]);
$sql = sprintf("insert into a_plan values('%s', '%s','%s','%s', '%s') ", $monthly_fee[0], $total_phone[0], $flow[0], $message[0], $video[0]);

//加入数据库
mysql_query($sql, $con);

// echo(gettype($monthly_fee[0]));
// $str=""."$monthly_fee[0]";
// echo($str);
// echo(gettype($str));

mysql_close($con);



?>