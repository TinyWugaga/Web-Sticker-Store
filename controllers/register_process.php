<?php

require __DIR__ . '/../etc/bootstrap.php';

//register new User 
if (!empty($_POST)) {

    // =============================================================================
    // = 處理送來的表單資料
    // =============================================================================

    $uAccount = $_POST["account"] ?? "";
    $uPassword = $_POST["password"] ?? "";
    $uName = $_POST["name"] ?? "";

    /* =============================================================================
     * = 確認帳號是否存在
     * =============================================================================
    **/

    $user = findUserByAccount($conn, $uAccount);

    if ($user) {
        header("Location:../register.php?msg=使用者已存在");
        die;
    }

    /* =============================================================================
     * = 新增使用者
     * =============================================================================
    **/

    $addResult = createUser($conn, [
        'account' => $uAccount,
        'password' => $uPassword,
        'name' => $uName,
    ]);

    // 跳轉並將結果帶回註冊頁面。
    header("Location:../register.php?result=" . $addResult);
    die();
}

header("Location:../stickerPage.php");
