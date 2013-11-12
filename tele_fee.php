<?php
function tele_fee(){
date_default_timezone_set("Asia/Harbin");
header("content-type: text/html; charset=utf-8");
set_time_limit(10000); //允许处理时间
require_once 'time_count.php';
require_once 'flow_count.php';
require_once 'fee.php';
require_once 'sort.php';


include "simple_html_dom.php";
// Create DOM from URL or file
$html = file_get_html('something/tele/tele_shihua.htm');



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
$pattern13  = "/<.*?>|[^0-9|^\.]|/"; //替换非数字不包括点
$table_c   = 0;
$arr=array();
// str_replace(' ','',$str);//去掉空格
$tele_fee=array();
//市话通话时间
$p_time=0;
foreach ($html->find('table') as $table) { 
    foreach ($table->find('tr') as $tr) {
        if($table_c==6)
        {
            // print_r($arr);
            // var_dump($arr);
            if($arr[7]=='主叫')
            {
                $p_time=$p_time+ceil($arr[5]/60);
                var_dump($arr[5]);
            }
            $arr=array();//初始化数组
        }
        foreach ($tr->find('td') as $td) {           
            if($table_c==6)
            {
                $tmp=preg_replace($pattern4, '', "$td");
                array_push($arr,$tmp);
             }
            }
    }
    $table_c++;
}

var_dump($p_time);

//长途通话时间
$html = file_get_html('something/tele/tele_changtu.htm');
$arr=array();
$table_c=0;
foreach ($html->find('table') as $table) { 
    foreach ($table->find('tr') as $tr) {
        if($table_c==6)
        {
            // print_r($arr);
            // var_dump($arr);
            if($arr[7]=='主叫')
            {
                $p_time=$p_time+ceil($arr[5]/60);
                var_dump($arr[5]);
            }
            $arr=array();//初始化数组
        }
        foreach ($tr->find('td') as $td) {           
            if($table_c==6)
            {
                $tmp=preg_replace($pattern4, '', "$td");
                array_push($arr,$tmp);
             }
            }
    }
    $table_c++;
}
echo('通话时间（分）'.'<br>');
var_dump($p_time);
$tele_fee[0]=$p_time;

//计算短信数量
$html = file_get_html('something/tele/tele_message.htm');
$table_c=0;
$arr=array();
$mess_c=0;
foreach ($html->find('table') as $table) { 
    foreach ($table->find('tr') as $tr) {
        if($table_c==6)
        {
            // print_r($arr);
            // var_dump($arr);
            if($arr[6]=='主叫')
            {
                $mess_c++;
                // var_dump($mess_c);
                // var_dump($arr);
            }
            $arr=array();//初始化数组
        }
        foreach ($tr->find('td') as $td) {                 
            if($table_c==6)
            {                
                $tmp=preg_replace($pattern4, '', "$td");
                // var_dump($tmp);
                array_push($arr,$tmp);
             }
            }
    }
    $table_c++;
}
echo('短信总条数'.'<br>');
 var_dump($mess_c);
 $tele_fee[2]=$mess_c;


//获取总流量
 $html = file_get_html('something/tele/tele_flow.htm');
 $tr_c=0;
 foreach ($html->find('table') as $table) { 
    foreach ($table->find('tr') as $tr) {
        if($tr_c==3)
        {
        foreach ($tr->find('td') as $td) {       
            $flow=preg_replace($pattern13, '', "$td");  
            echo('总流量KB'.'<br>');     
            $flow=$flow*1024;   
            var_dump($flow);
             $tele_fee[1]=$flow;
            break;
         }
     }
     $tr_c++;
    }
}
$fee_0=array();
$fee_0_0=array();

$plan_fee=fee($p_time,$flow,$mess_c);
$fee_0_0[0]=$tele_fee;
$fee_0_0[1]=sort_tele($plan_fee);
$fee_0_0[2]=sort_all($plan_fee);

$fee_0[0]=$fee_0_0[0];//月消费详情
$fee_0[1]=$fee_0_0[1];//存储联通套餐费用
$fee_0[2]=$fee_0_0[2];//存储三大运营商套餐费用
return $fee_0;
}

?> 