<?php
// Test CVS
function union_fee(){
header("content-type: text/html; charset=utf-8");
require_once 'Excel/reader.php';
require_once 'time_count.php';
require_once 'fee.php';
require_once 'sort.php';

$union_fee=array();

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('UTF-8');

$data->read('something/union/phone_time.xls');


error_reporting(E_ALL ^ E_NOTICE);

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
  if($data->sheets[0]['cells'][$i][5]=='主叫'){
        $tmp=$data->sheets[0]['cells'][$i][4];
        $arr=time_arr($tmp);   
        $p_time=$p_time+time_count($arr[0]);
        // echo($p_time);
        
}
}
echo($data->sheets[0]['cells'][1][4]);
echo '<br>';
echo($p_time);
echo '<br>';
$union_fee[0]=$p_time;



$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read('something/union/flow.xls');

error_reporting(E_ALL ^ E_NOTICE);

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        $flow=$flow+$data->sheets[0]['cells'][$i][6]; 
}
echo($data->sheets[0]['cells'][1][6]);
echo '<br>';
echo($flow);
echo '<br>';
$union_fee[1]=$flow;


$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read('something/union/message.xls');

error_reporting(E_ALL ^ E_NOTICE);

$mess_c=$data->sheets[0]['numRows']-1;

echo('短信数量');
echo '<br>';
echo($mess_c);
echo '<br>';
$union_fee[2]=$mess_c;

var_dump($union_fee);

$fee_0=array();
$fee_0_0=array();

$plan_fee=fee($p_time,$flow,$mess_c);
$fee_0_0[0]=$union_fee;
$fee_0_0[1]=sort_union($plan_fee);
$fee_0_0[2]=sort_all($plan_fee);

$fee_0[0]=$fee_0_0[0];//月消费详情
$fee_0[1]=$fee_0_0[1];//存储联通套餐费用
$fee_0[2]=$fee_0_0[2];//存储三大运营商套餐费用
var_dump($fee_0);
return $fee_0;
}


?>
