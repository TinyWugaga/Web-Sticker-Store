<?php

require __DIR__ . '/etc/bootstrap.php';

//獲取登入資訊 未登入$user則為空值
$user = findUserById($conn, $id = $_SESSION["userId"] ?? "");
//如有登入資訊 獲取登入使用者 ID/帳號/名稱/身份
$userId    = $user["id"] ?? "";
$account   = $user['account'] ?? "";
$name      = $user['name'] ?? "";
$authority = $user['role'] ?? "";

//非管理者則跳回貼圖商店
if ( $authority != 'M') {
    header("Location:stickerPage.php");
    die;
}

//檢查是否有排序及搜尋條件 
$sort   = $_GET["sort"] ?? "id";
$search = $_GET["search"] ?? "";
$field  = $_GET["field"] ?? "account";

//創建使用者清單欄位 及獲取使用者清單
$usersTitle = [
    "id"         => "編號",
    "role"       => "身份",
    "account"    => "帳號",
    "password"   => "密碼",
    "name"       => "名稱",
    "created_at" => "創建日期",
    "updated_at" => "更新日期",
];
$usersList = findUserLikeSearch($conn, $search, $field, $sort);

//獲取要修改的使用者資料
$editId = $_GET["edit"] ?? $_POST["edit"] ?? '0' ;
$editUser = findUserById($conn, $editId);

//獲取要刪除的使用者資料
$deleteId = $_GET["delete"] ?? $_POST["delete"] ?? '0' ;

//獲取process結果
$result = isset($_GET["result"]) ? $_GET["result"] ? '成功':'失敗' : '';

?>

<html>

<?php include("header.php") ?>

<body class="page__userTable">
    <div class="wrapper">

        <header class="header">
            <h1 class="header__logo">
                <a href="stickerPage.php"><span>WEB</span>STORE</a>
            </h1>
            <div class="header__search" data-widget="SearchBox">
                <form action="usersTable.php" method="GET">
                    <span class="header__search_block header__search_block--filter">
                        <i class="material-icons icon-filter">filter_list</i>
                        <select class="search__filter_select" name="field">
                            <option value="account">帳號</option>
                            <option value="name">名稱</option>
                        </select>
                    </span>
                    <span class="header__search_block">
                        <input class="search__submit_input" type="submit" value="">
                        <i class="material-icons icon-search">search</i>
                        </input>
                    </span>
                    <input class="header__search_input" type="text" name="search" placeholder="搜尋使用者" value=<?= $search ?>>
                </form>
            </div>
            <ul class="header__util">
                <li class="header__util_item wish-box">
                    <a><span>你好，<?= $name ?></span></a>
                    <span class="util__item_line">|</span>
                </li>
                <li class="header__util_item">
                    <a href="stickerPage.php">
                        <span>返回貼圖商店</span>
                    </a>
                    <span class="util__item_line">|</span>
                </li>
                <li class="header__util_item login-button">
                    <a href="controllers/logout_process.php">登出</a>
                </li>
            </ul>
        </header>

        <div class="content">
            <!-- EDIT BOARD -->
            <?php if($editId) { ?>
            <div class="class__modal class__edit">
                <div class="class__board">
                    <div class="class__board_inner">
                        <div class="class__board_logo">
                            <h1 class="class__board_title">Edit</h1>
                        </div>
                        <?php if($result){ ?>
                        <p class="class__board_notice"> 修改資料<?= $result ?></p>
                        <?php } ?>
    
                        <div class="class__board_block">
                            <form class="class__form" name="updateForm" action="controllers/edit_process.php" method="post">
                                <input type="hidden" name="id" value=<?= $editUser['id'] ?> >
                                <div class="class__form_textField form__textField--disabled">
                                    <label class="form__textField_label"><?= $editUser['account'] ?>  </label>
                                </div>
                                <div class="class__form_textField">
                                    <label class="form__textField_label">密碼</label>
                                    <input type="text" name="password" placeholder="修改密碼" value="<?= $editUser['password'] ?>" required>
                                </div>
                                <div class="class__form_textField">
                                    <label class="form__textField_label">名稱</label>
                                    <input type="text" name="name" placeholder="修改名稱" value="<?= $editUser['name'] ?>" required>
                                </div>
                                <div class="class__form_btn">
                                    <button type="submit" class="btn submit__btn">修改</button>
                                    <button type="button" class="btn cancel__btn">
                                        <a href="usersTable.php">取消</a>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
             <!-- DELETE BOARD -->
            <?php if($deleteId) { ?>
            <div class="class__modal class__delete">
                <div class="class__board">
                    <div class="class__board_inner">
                        <div class="class__board_logo">
                            <h1 class="class__board_title">Delete</h1>
                        </div>
                        <?php if($result){ ?>
                        <p class="class__board_notice">刪除使用者<?= $result ?></p>
                        <div class="class__board_block">
                            <div class="class__form_btn">
                                <button type="button" class="btn submit__btn">
                                    <a href="usersTable.php">確認</a>
                                </button>
                            </div>
                        </div>
                        <?php }else{?>
                        <p class="class__board_text">是否確定要刪除使用者？</p>
                        <div class="class__board_block">
                            <form class="class__form" name="deleteForm" action="controllers/delete_process.php" method="post">
                                <input type="hidden" name="id" value=<?= $deleteId ?> >
                                <div class="class__form_btn">
                                    <button type="submit" class="btn submit__btn">確認</button>
                                    <button type="button" class="btn cancel__btn">
                                        <a href="usersTable.php">取消</a>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
             <!-- USER TABLE -->
            <div class="class__paper">
                <table class="class__table">
                    <thead class="class__table_head">
                        <tr class="class__table_row">
                            <?php foreach ($usersTitle as $field => $title) { ?>
                                <th class="class__table_cell class__table_cell--head">
                                    <a class="icon table__cell_button" href="usersTable.php?sort=<?= $title ?>&search=<?= $search ?>&field=<?= $field ?>">
                                        <?= $title ?>
                                        <i class="material-icons">expand_more</i>
                                    </a>
                                </th>
                            <?php } ?>
                            <th class="class__table_cell class__table_cell--head table__cell--icon">編輯</th>
                            <th class="class__table_cell class__table_cell--head table__cell--icon">刪除</th>
                        </tr>
                    </thead>

                    <tbody class="class__table_content">
                        <?php foreach ($usersList as $user) { ?>
                            <tr class="class__table_row class__table_row--body">
                                <?php foreach ($usersTitle as $field => $title) { ?>
                                    <td class="class__table_cell class__table_cell--body">
                                        <?= $user->$field ?>
                                    </td>
                                <?php } ?>
                                <td class="class__table_cell class__table_cell--body table__cell--icon">
                                 
                                    <form action="usersTable.php" method="post">
                                        <a class="table__cell_button">
                                            <input type="submit" name="edit" value="<?= $user->id ?>">
                                                <i class="material-icons">edit</i>
                                            </input>
                                        </a>
                                    </form>
                                </td>
                                <td class="class__table_cell class__table_cell--body table__cell--icon">
                                    <form action="usersTable.php" method="post">
                                        <a class="table__cell_button">
                                            <input type="submit" name="delete" value="<?= $user->id ?>">
                                                <i class="material-icons icon-delete">delete</i>
                                            </input>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
</body>

</html>