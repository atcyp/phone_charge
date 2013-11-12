<?php

//p_time,flow,mess_c.
//遍历数据库里面所有表，算出所有套餐的费用
//返回一个plan_fee数组，数组格式输出可看
function fee($p_time,$flow,$mess_c)
{
    //连接数据库
$con = mysql_connect('localhost', 'root', '') or 
    die ("connect failed" . mysql_error());
mysql_select_db("phone_charge") or die(mysql_error());
mysql_query("set names 'utf8'");

$plan=array(
'a_plan',
'b_plan',
'c_plan',
'yidong',
'tele'
    );
$plan_fee=array(
'a_plan'=>'',
'b_plan'=>'',
'c_plan'=>'',
'yidong'=>'',
'tele'=>''
    );
$fee_arr=array();
for($i=0;$i<count($plan);$i++)
{
  $sql = sprintf(
      "SELECT  `monthly_fee` ,  `total_phone` ,  `flow` ,  `message`, `phone_m`, `flow_k`,  `message_m` 
      FROM  `%s`",$plan[$i]
      );
//这里面`%s`假如改为'%s'编译不通过，fuck
 $result = mysql_query($sql, $con);

 while($row=mysql_fetch_array($result)){
       // var_dump($row);
           $arr= array(
        "monthly_fee" => $row["monthly_fee"],
        "total_phone" => $row["total_phone"],
        "flow" => $row["flow"],
        "message" => $row["message"],
        "phone_m" => $row["phone_m"],
        "flow_k" => $row["flow_k"],
        "message_m" => $row["message_m"]
        );
           var_dump($arr);
           // var_dump($row);
           $fee=0;
           //计算通话花费
           $tmp=$p_time-$arr['total_phone'];
           if($tmp>0)
                 $tmp=$tmp*$arr['phone_m'];
           else      
                $tmp=0;
            $fee=$fee+$tmp;
            //计算流量花费
            $tmp=$flow-$arr['flow']*1024;
            if($tmp>0)
                $tmp=$tmp*$arr['flow_k'];
            else
                $tmp=0;       
         $fee=$fee+$tmp;
            //计算短信花费
            $tmp=$mess_c-$arr['message'];
            if($tmp>0)
                $tmp=$tmp*$arr['message_m'];
            else
                $tmp=0;
             $fee=$fee+$tmp;
             $fee=$fee+$arr['monthly_fee'];
             var_dump($fee);

             if($plan[$i]=='a_plan')
             {
              $aa='A计划'.$arr['monthly_fee'].'元套餐';
             }
             else if($plan[$i]=='b_plan')
             {
              $aa='B计划'.$arr['monthly_fee'].'元套餐';
             }
              else if($plan[$i]=='c_plan')
             {
              $aa='C计划'.$arr['monthly_fee'].'元套餐';
             }
             else if($plan[$i]=='yidong')
             {
              $aa='移动'.$arr['monthly_fee'].'元套餐';
             }
             else
             {
               $aa='电信'.$arr['monthly_fee'].'元套餐';
             }
            $fee_arr["$aa"]=$fee;
             // $tmp2=$arr['monthly_fee'];
             // $tmp3="$plan[$i]".'_'.$tmp2;

             // $fee_arr["$tmp3"]=$fee;


             var_dump($fee_arr);
    }
    $plan_fee["$plan[$i]"]=$fee_arr;
    $fee_arr=array();
    var_dump($plan_fee);
}

mysql_close($con);
return $plan_fee;
}
?>