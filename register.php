<?php

session_start();

//接收註冊狀態訊息
$msg = $_GET["msg"] ?? "";
if(isset($_GET["result"])){ 
  $msg = $_GET["result"] ? "帳號註冊成功" : "帳號註冊失敗";
};

?>

<!DOCTYPE html>

<html>

<?php include("header.php") ?>

<body class="page__login">
  <div class="wrapper">
    <div class="container">

      <div class="class__board">
        <div class="class__board_inner">
          <div class="class__board_logo">
            <h1 class="class__board_title">WEB REGISTER</h1>
          </div>

          <p class="class__board_notice"> <?= $msg ?></p>

          <div class="class__board_block">
            <form class="class__form" name="loginForm" action="controllers/register_process.php" method="post">
              <div class="class__form_textField">
                <label class="form__textField_label">帳號</label>
                <input type="text" name="account" placeholder="請輸入註冊帳號" required autocapitalize="off" autocorrect="off" spellcheck="false">
              </div>
              <div class="class__form_textField">
                <label class="form__textField_label">密碼</label>
                <input type="password" name="password" placeholder="請輸入註冊密碼" required>
              </div>
              <div class="class__form_textField">
                <label class="form__textField_label">使用者名稱</label>
                <input type="text" name="name" placeholder="請輸入使用者名稱" required>
              </div>
              <div class="class__form_btn">
                <button type="submit" class="btn submit__btn">註冊</button>
              </div>
            </form>
          </div>

          <div class="class__board_text">
            <a href="login.php" class="board__text_link">已有帳號？前往登入</a>
          </div>
        </div>

      </div>
    </div>
  </div>

</body>

</html>