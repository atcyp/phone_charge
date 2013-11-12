<?php

//考虑小数点
//17M776.85K
//M前的数字必为整数,K前的数字可为浮点数
function flow_count($str)
{
    $flag=false;
    $m=0;
    $k=0;
    $c=0;
    for($i=0;$i<strlen($str);$i++)
    {
        // $tmp=strlen($str);
        // var_dump($tmp);
        if($str[$i]=='G')
        {
            $k=$k+$m*1024*1024;
            $flag=false;
             $c=0;
             $m=0;
            continue;
         }
          else if($str[$i]=='M')        
        {
            $k=$k+$m*1024;
            $flag=false;
             $c=0;
             $m=0;
            continue;
        }
          else if($str[$i]=='K')
        {
            $k=$k+$m;
            $flag=false;
             $c=0;
             $m=0;
            continue;
        }
        else if($str[$i]=='.')
        {
            $flag=true;
            continue;
        }
        else if($str[$i]>=0&&$str[$i]<=9&&$flag==false)
        {
            $m=$m*10+$str[$i];
        }
           else if($str[$i]>=0&&$str[$i]<=9&&$flag==true)
        {
            $c++;
            $m=$str[$i]/pow(10,$c)+$m;
        }
        else
            continue;
    }
return $k;
}

?>