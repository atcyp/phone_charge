<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>表格</title>
<!-- <link href="table/styles.css" rel="stylesheet" type="text/css" /> -->
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="ineedagirlfriend/css/font-face.css">
<link rel="stylesheet" href="ineedagirlfriend/css/color-schemes/dark/hot-pink.css">
<link rel="stylesheet" href="ineedagirlfriend/css/typography.css">
<link rel="stylesheet" href="ineedagirlfriend/css/font-awesome.css">
<link rel="stylesheet" href="ineedagirlfriend/css/main.css">
<script src="ineedagirlfriend/js/plugins.js"></script>
<script src="ineedagirlfriend/js/main.js"></script>

<script language="javascript" type="text/javascript" src="table/jquery.js"></script>
<script language="javascript" type="text/javascript" src="table/jquery.bstablecrosshair.js"></script>

</head>
<body>

<div class="top-ribbon"></div>
<div class="home">
    <a href="http://localhost/phone_charge/ineedagirlfriend/index.html"> <i class="icon-home"></i>
    </a>
</div>

<!-- 上传文件的代码 -->
<?php
// require_once 'yidong_fee.php';
$mod= $_POST['mod'];  
if($mod=='yd'){
require_once 'upload_file_yd.php';
}
else  if($mod=='union')
{
    require_once 'upload_file_union.php';
}
else  if($mod=='tele')
{
    require_once 'upload_file_tele.php';
}
else
echo 'Error'.'<br>
';
?>
<!-- 用户月消费情况 -->
<table style="border:2px solid #444;border-collapse:collapse;" id="mytable">
    <tr>
        <td>通话时间/min</td>
        <td>使用流量/KB</td>
        <td>短信数量/条</td>
    </tr>
    <?php
    if($mod=='yd'){
require_once 'yidong_fee.php';
$fee=yidong_fee();
}
else  if($mod=='union')
{
    require_once 'union_fee.php';
    $fee=union_fee();
}
else  if($mod=='tele')
{
    require_once 'tele_fee.php';
    $fee=tele_fee();
}
else
echo 'Error'.'<br>
    ';

    
    echo
    '
    <tr>
        '.
        '
        <td>'.$fee[0][0].'</td>
        '.
        '
        <td>'.$fee[0][1].'</td>
        '.
        '
        <td>'.$fee[0][2].'</td>
        '.
    '
    </tr>
    ';

?>
</table>
<script type="text/javascript">
$.bstablecrosshair('mytable',{color:'#444',background:'#aaa','foreground':'#fff'});
</script>
<br></br>

<!-- 移动的套餐排序表格 -->
<table style="border:2px solid #444;word-break:break-all; word-wrap:break-all;" id="mytable2">
<?php
    echo     
    '<tr>
'.
        '
<td>'.'套餐'.'</td>
';
    foreach ($fee[1] as $key => $value) 
    {
    echo
    '
<td>'.$key. '</td>
';
    }
echo  '
</tr>
';

    echo     
    '
<tr>
'.
        '
<td>'.'费用'.'</td>
';
    foreach ($fee[1] as $key => $value) 
    {
    echo
    '
<td>'.$value. '</td>
';
    }
echo  '
</tr>
';

?>
</table>
<script type="text/javascript">
$.bstablecrosshair('mytable2',{color:'#444',background:'#aaa','foreground':'#fff'});
</script>

</table>
<script type="text/javascript">
$.bstablecrosshair('mytable',{color:'#444',background:'#aaa','foreground':'#fff'});
</script>
<br></br>
<!-- 所有的套餐排序表格 -->
<!-- <table style="border:2px solid #444;word-break:break-all; word-wrap:break-all;" id="mytable3">
-->
<table style="border:2px solid #444" id="mytable3">
<?php
    echo     
    '<tr>
'.
        '
<td>'.'套餐'.'</td>
';
    foreach ($fee[2] as $key => $value) 
    {
    echo
    '
<td>'.$key. '</td>
';
    }
echo  '
</tr>
';

    echo     
    '
<tr>
'.
        '
<td>'.'费用'.'</td>
';
    foreach ($fee[2] as $key => $value) 
    {
    echo
    '
<td >'.$value. '</td>
';
    }
echo  '
</tr>
';

?>
</table>
<script type="text/javascript">
$.bstablecrosshair('mytable3',{color:'#444',background:'#aaa','foreground':'#fff'});
</script>
<br />
<hr />
<div style="text-align:center;clear:both"></div>
</body>
</html>