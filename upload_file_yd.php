<?php
// function up_yd(){
// require_once 'yidong_fee.php';
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  echo "Type: " . $_FILES["file"]["type"] . "<br />";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];

$dest='C:/wamp/www/phone_charge/something/yidong/';//设定上传目录


if (file_exists("$dest" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],"$dest" . "yd_phone.htm");
      echo "Stored in: " . "$dest" . $_FILES["file"]["name"];

      //跳转到表格页面
      // echo "<script type='text/javascript'>location.href='http://localhost/phone_charge/table.php'</script>";
      }

  // }
}
?>