<?php
//以u模式提取$str里面的中文和数字，存为复合数组，而且返回符合数组第一个元素仍为一个数组，此数组为所需要的数据
function time_arr($str)
{
    preg_match_all("/./u", $str, $arr);//.表示任何字符，模式修正符u

     // print_r($arr);
     // echo'<br>';
    return $arr;
}
//分秒的判断必须放前面，因为中文长度为3，如果先判断数字会将中文字节拆成3个0（类型转为int）
function time_count($time_arr)
{
    $sec=0;
    $min=0;
    for($i=0;$i<count($time_arr);$i++)
    {
        if($time_arr[$i]=='时')        
        {
            $min=$min+$sec*60;
            continue;
        }
           else  if($time_arr[$i]=='分')        
        {
            $min=$min+$sec;
            continue;
        }
          else if($time_arr[$i]=='秒')
        {
            $min+=1;
            continue;
        }
        else if($time_arr[$i]>=0&&$time_arr[$i]<=9)
        {
            $sec=$sec*10+$time_arr[$i];
        }
        else
            continue;
    }
return $min;
}
?>