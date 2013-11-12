<?php
//套餐费用的排序函数
function sort_union($plan_fee)
{
    $union_arr=$plan_fee['a_plan']+$plan_fee['b_plan']+$plan_fee['c_plan'];
    asort($union_arr);
    var_dump($union_arr);
    return $union_arr;
}

function sort_yidong($plan_fee)
{
    $yd_arr=$plan_fee['yidong'];
   asort($yd_arr);
    var_dump($yd_arr);
    return $yd_arr;

}

function sort_tele($plan_fee)
{
    $tele_arr=$plan_fee['tele'];
    asort($tele_arr);
    var_dump($tele_arr);
    return $tele_arr;
}

function sort_all($plan_fee)
{
    $all_arr=$plan_fee['a_plan']+$plan_fee['b_plan']+$plan_fee['c_plan']+$plan_fee['yidong']+$plan_fee['tele'];
    asort($all_arr);
    var_dump($all_arr);
    return $all_arr;
}
?>