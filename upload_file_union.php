<?php
// require_once 'yidong_fee.php';

$arr=array(
'file1',
'file2',
'file3'
  );
$name=array(
'phone_time.xls',
'flow.xls',
'message.xls'
  );
for($i=0;$i<count($arr);$i++)
{
if ($_FILES["$arr[$i]"]["error"] > 0)
  {
  echo "Error: " . $_FILES["$arr[$i]"]["error"] . "<br />";
  }
else
  {
  echo "Upload: " . $_FILES["$arr[$i]"]["name"] . "<br />";
  echo "Type: " . $_FILES["$arr[$i]"]["type"] . "<br />";
  echo "Size: " . ($_FILES["$arr[$i]"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["$arr[$i]"]["tmp_name"];

$dest='C:/wamp/www/phone_charge/something/union/';//设定上传目录


if (file_exists("$dest" . $_FILES["$arr[$i]"]["name"]))
      {
      echo $_FILES["$arr[$i]"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["$arr[$i]"]["tmp_name"],"$dest" . $name[$i]);
      echo "Stored in: " . "$dest" . $_FILES["$arr[$i]"]["name"];
     // $yd_fee= yidong_fee();
     // var_dump($yd_fee);
      }

  }
}
?>