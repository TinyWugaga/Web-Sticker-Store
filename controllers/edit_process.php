<?php

require __DIR__ . '/../etc/bootstrap.php';

//確認是否有修改表單資料
if (!empty($_POST)) {

    // =============================================================================
    // = 處理送來的表單資料
    // =============================================================================

    $uId = $_POST["id"] ?? "";
    $uAccount = $_POST["account"] ?? "";
    $uPassword = $_POST["password"] ?? "";
    $uName = $_POST["name"] ?? "";

    /* =============================================================================
     * = 修改使用者資料
     * =============================================================================
    **/

    $updateResult = updateUser($conn, $uId, [
        'account' => $uAccount,
        'password' => $uPassword,
        'name' => $uName,
    ]);

    // 跳轉並將結果帶回修改頁面。
    header("Location:../usersTable.php?edit={$uId}&result={$updateResult}");
    die();
}

header("Location:../stickerPage.php");
