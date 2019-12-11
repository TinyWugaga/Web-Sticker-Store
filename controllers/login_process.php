<?php

require __DIR__ . '/../etc/bootstrap.php';

$uAccount = $_POST["account"] ?? "";
$uPassword = $_POST["password"] ?? "";

//確認使用者存在，如果存在回傳該帳號資料
$user = findUserByAccount($conn, $uAccount);

//確認 $user非空值 以及密碼符合該帳號
if ($user && $uPassword == $user["password"])
{
  $_SESSION["userId"] = $user['id'];
}
else
{
  $msg = 'failed';
  header("Location:../login.php?msg=" . $msg);
  die();
}

header("Location:../stickerPage.php");
