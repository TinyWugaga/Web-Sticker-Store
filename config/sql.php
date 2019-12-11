<?php

require __DIR__ . '/../models/users.php';
require __DIR__ . '/../models/stickers.php';

// =============================================================================
// = Users
// =============================================================================

/**
 * 獲取所有欄位名稱
 * 
 * @param  PDO $conn     PDO實體
 * @param  array $data   要新增的使用者資料
 * @return boolean       執行結果
 */
function fetchAllUsersField($conn)
{
    $stmt = $conn->prepare('SHOW COLUMNS FROM `users`');
    $stmt->execute();

    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function($column){
        return $column['Field'];
    }, $columns);
}

/**
 * 取得所有使用者
 * 
 * @param  PDO $conn    PDO實體
 * @return object
 */
function fetchAllUser($conn)
{
    $stmt = $conn->prepare('SELECT * FROM `users`');
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Users');
}

/**
 * 依照給予的ID，取得使用者
 * 
 * @param  PDO $conn       PDO實體
 * @param  string $id      要搜尋的使用者ID
 * @return array
 */
function findUserById($conn, $id)
{
    $stmt = $conn->prepare('SELECT * FROM `users` WHERE `id`=:id');
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {//轉換願望清單json格式
        $user['wish_list'] = json_decode($user['wish_list'],JSON_UNESCAPED_UNICODE);
    }
    return $user;
}

/**
 * 依照給予的帳號，取得使用者
 * 
 * @param  PDO $conn       PDO實體
 * @param  string $account 要搜尋的使用者帳號
 * @return array
 */
function findUserByAccount($conn, $account)
{
    $stmt = $conn->prepare('SELECT * FROM `users` WHERE `account`=:account');
    $stmt->execute(['account' => $account]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user) {//轉換願望清單json格式
        $user['wish_list'] = json_decode($user['wish_list'],JSON_UNESCAPED_UNICODE);
    }

    return $user;
}

/**
 * 依照給予的欄位與關鍵字，取得符合的使用者
 * 
 * @param  PDO $conn       PDO實體
 * @param  string $search  要搜尋的關鍵字
 * @param  string $field   要依此搜尋關鍵字的欄位
 * @param  string $sort    要依此排序結果的欄位
 * @return object
 */
function findUserLikeSearch($conn, $search, $field, $sort)
{

    $sql = <<<HEREDOC
    SELECT *
    FROM `users`
    WHERE `{$field}` LIKE :search 
    AND `deleted_at` IS NULL
    ORDER BY `{$sort}` ASC
    HEREDOC;

    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%{$search}%"]);

    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Users');
}

/**
 * 新增使用者
 * 
 * @param  PDO $conn     PDO實體
 * @param  array $data   要新增的使用者資料
 * @return boolean       執行結果
 */
function createUser($conn, $data = [])
{
    $stmt = $conn->prepare(
        'INSERT INTO `users` (`role`, `account`, `password`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES (:role, :account, :password, :name, :created_at, :updated_at, :deleted_at)'
    );
    
    return $stmt->execute([
        'role'       => $data['role'] ?? 'C',
        'account'    => $data['account'],
        'password'   => $data['password'],
        'name'       => $data['name'],
        'created_at' => $data['created_at'] ?? date('Y-m-d'),
        'updated_at' => $data['updated_at'] ?? null,
        'deleted_at' => $data['deleted_at'] ?? null,
    ]);
}

/**
 * 修改使用者資料
 * 
 * @param  PDO $conn     PDO實體
 * @param  string $id    要修改的使用者編號
 * @param  array $data   要修改的使用者資料
 * @return boolean       執行結果
 */
function updateUser($conn, $id, $data = [])
{    
    $stmt = $conn->prepare(
        "UPDATE `users` SET `account`=:account, `password`=:password, `name`=:name, `updated_at`=:updated_at WHERE `id`={$id}"
    );
    
    return $stmt->execute([
        'account'    => $data['account'],
        'password'   => $data['password'],
        'name'       => $data['name'],
        'updated_at' => $data['updated_at'] ?? date('Y-m-d H:i:s'),
    ]);

}

/**
 * 更新使用者願望清單資料
 * 
 * @param  PDO $conn     PDO實體
 * @param  string $id    要更新清單的使用者編號
 * @param  array $data   更新後的願望清單資料
 * @return boolean       執行結果
 */
function updateWishList($conn, $id, $list = [])
{    
    $wish_list = json_encode($list,JSON_UNESCAPED_UNICODE);

    $stmt = $conn->prepare(
        "UPDATE `users` SET `wish_list`=:wish_list, `updated_at`=CURRENT_TIME() WHERE `id`={$id}"
    );
    
    return $stmt->execute(['wish_list' => $wish_list]);

}

/**
 * 軟刪除使用者資料
 * 
 * @param  PDO $conn     PDO實體
 * @param  string $id    要刪除的使用者編號
 * @return boolean       執行結果
 */
function deleteUser($conn, $id)
{    
    $stmt = $conn->prepare(
        "UPDATE `users` SET `deleted_at`= CURRENT_TIME() WHERE `id`={$id}"
    );
    
    return $stmt->execute();
}

// =============================================================================
// = Stickers
// =============================================================================

/**
 * 取得所有貼圖
 * 
 * @param  PDO $conn    PDO實體
 * @return object
 */
function fetchAllStickers($conn)
{
    $stmt = $conn->prepare('SELECT * FROM `stickers`');
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Stickers');
}

/**
 * 依照給予的id獲取貼圖資訊
 * 
 * @param  PDO $conn    PDO實體
 * @param  string $id   要搜尋的貼圖ID
 * 
 * @return array
 */
function findStickerById($conn, $id)
{
    $stmt = $conn->prepare('SELECT * FROM `stickers` WHERE `id`=:id');
    $stmt->execute([ 'id' => $id ]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * 依照給予的欄位與關鍵字，取得符合的使用者
 * 
 * @param  PDO $conn       PDO實體
 * @param  string $search  要搜尋的關鍵字
 * @param  string $field   要依此搜尋關鍵字的欄位
 * @param  string $sort    要依此排序結果的欄位
 * @return object
 */
function findStickerLikeSearch($conn, $search, $field)
{
    $stmt = $conn->prepare("SELECT * FROM `stickers` WHERE `{$field}` LIKE :search");
    $stmt->execute(['search' => "%{$search}%"]);

    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Stickers');
}


// =============================================================================
// = Purchases
// =============================================================================

/**
 * 新增購買紀錄
 * 
 * @param  PDO $conn     PDO實體
 * @param  array $data   要新增的購買記錄資料
 * @return boolean       執行結果
 */
function addPurchase($conn, $data = [])
{
    $stmt = $conn->prepare(
        'INSERT INTO `purchases` (`user_id`, `sticker_id`, created_at) VALUES (:user_id, :sticker_id, :created_at)'
    );
    
    return $stmt->execute([
        'user_id'       => $data['user_id'],
        'sticker_id'    => $data['sticker_id'],
        'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s'),
    ]);
}

/**
 * 依使用者id回傳已購買的貼圖清單
 * 
 * @param  PDO $conn     PDO實體
 * @param  string $id    要搜尋購買記錄的使用者
 * @return array
 */
function userPurchasedList($conn, $user_id)
{
    $sql = "SELECT * FROM `purchases` WHERE `user_id` = '$user_id'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $recordList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userPurchase = array_map(function ($record){
        return $record['sticker_id'];
    },$recordList);

    return $userPurchase;
}