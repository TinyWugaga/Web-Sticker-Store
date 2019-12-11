<?php

require __DIR__ . '/../etc/bootstrap.php';

//確認是否有修改表單資料
if (!empty($_POST)) {

    // =============================================================================
    // = 處理送來的表單資料
    // =============================================================================

    $userId = $_POST["userId"] ?? "";
    $stickerId = $_POST["stickerId"] ?? "";

    //沒有登入資訊跳轉登入頁
    if(!$userId){
        header("Location:../login.php");
        die;
    }
    
    /* =============================================================================
     * = 修改使用者資料
     * =============================================================================
    **/
   
    $purchaseResult = addPurchase($conn, [
        "user_id" => $userId,
        "sticker_id" => $stickerId,
    ]);

    header("Location:../stickerPage.php?sticker=$stickerId&purchase=$purchaseResult");
    die();
}

header("Location:../stickerPage.php");
