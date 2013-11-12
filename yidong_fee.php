<?php
function yidong_fee()
{
date_default_timezone_set("Asia/Harbin");
header("content-type: text/html; charset=utf-8");
set_time_limit(10000); //允许处理时间
require_once 'time_count.php';
require_once 'flow_count.php';
require_once 'fee.php';
require_once 'sort.php';

include "simple_html_dom.php";
// Create DOM from URL or file
$html = file_get_html('something/yidong/yd_phone.htm');

$yd_fee=array();

// Find all <td> in <table> which class=hello
// $es = $html->find('table.hello td');

$pattern   = "/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0$/"; //非负浮点数的正则表达式
$pattern1  = "/^[A-Z]+$/"; //大写英文字母字符串正则表达式
$pattern2  = "/[0-9]+\d/"; //匹配正整数，无法匹配个位数
$pattern3  = "/MB|GB/";
$pattern4  = "/<.*?>/";
$pattern5  = "/>\d+</";
$pattern6  = "/([1-9]+\d)|[0]/"; //匹配非负整数
$pattern7  = "/([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0)|([1-9]+\d)/";
$pattern8  = "/(<.*?>)|MB|GB/";
$pattern9  = "/<.*?>|[^0-9]/"; //替换非数字
$pattern10 = "/[\x{4e00}-\x{9fa5}]+/u"; //替换中文
$pattern11= "/总流量|WLAN总流量/u";
$pattern12 = "/\(|\)|B|(<.*?>)|[\x{4e00}-\x{9fa5}]+/u"; //替换中文，左右括号，还有字母B
$table_c   = 0;
$flag=false;
$count=0;
//话费详单共有17个table 
//第5个table每个tr里面的第5个td即为通话时间
foreach ($html->find('table') as $table) { 
    foreach ($table->find('tr') as $tr) {
        foreach ($tr->find('td') as $td) {           
            $tmp= preg_replace($pattern4, "", "$td");
            //计算通话总时间
            $count++;
            if($table_c==5&&$count==5){                         
                // var_dump($tmp);    
                 $arr=time_arr($tmp);   
                  $p_time=$p_time+time_count($arr[0]);
                  // var_dump($p_time);
            }
            // if($table_c==5)
            //      var_dump("$td");
//获取短信总条数
             if($flag==true){
                $flag=false;
                $tmp=preg_replace($pattern10, "", $tmp);
                echo('短信总条数'.'<br>');
                var_dump($tmp);
                $yd_fee[2]=$tmp;
            }
            if($tmp=='短信总条数:'){
                $flag=true;
            }
            //获取流量
              if (preg_match($pattern11, $td, $matches) == 1) {
                        $tmp = $matches[0]; //正则匹配提取关键字总流量，而且避免WLAN总流量的冲突
                        if($tmp=='总流量')  {
                            var_dump("$td");
                            $tmp=preg_replace($pattern12,'',"$td");
                            var_dump($tmp);
                            //17MB776.85KB
                            $tmp=flow_count($tmp);
                            echo('总流量/KB'.'<br>');
                            var_dump($tmp);
                            $yd_fee[1]=$tmp;
                        }                        
                    }           
        }
        $count = 0;//每个tr之后将td计数器置0
    }
    if($table_c==5){
        echo('通话时间（分）'.'<br>');
        var_dump($p_time);
        $yd_fee[0]=$p_time;
    }
            $table_c++;
    var_dump($table_c);
}
$fee_0=array();
$fee_0_0=array();

$plan_fee=fee($p_time,$flow,$mess_c);
$fee_0_0[0]=$yd_fee;
$fee_0_0[1]=sort_yidong($plan_fee);
$fee_0_0[2]=sort_all($plan_fee);

$fee_0[0]=$fee_0_0[0];//月消费详情
$fee_0[1]=$fee_0_0[1];//存储联通套餐费用
$fee_0[2]=$fee_0_0[2];//存储三大运营商套餐费用
var_dump($fee_0);
return $fee_0;
}

?> 