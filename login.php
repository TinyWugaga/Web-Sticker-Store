<?php

session_start();

if (isset($_SESSION["account"])) {
  header("Location:stickerPage.php");
};

//verified failed msg
$msg = isset($_GET["msg"]) ? "帳號密碼輸入錯誤" : "";

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
            <h1 class="class__board_title">WEB</h1>
          </div>

          <p class="class__board_notice"> <?= $msg ?></p>

          <div class="class__board_block">
            <form class="class__form" name="loginForm" action="controllers/login_process.php" method="post">
              <div class="class__form_textField">
                <label class="form__textField_label">帳號</label>
                <input type="text" name="account" placeholder="帳號" required autocapitalize="off" autocorrect="off" spellcheck="false">
              </div>
              <div class="class__form_textField">
                <label class="form__textField_label">密碼</label>
                <input type="password" name="password" placeholder="密碼" required>
              </div>
              <div class="class__form_btn">
                <button type="submit" class="btn submit__btn">登入</button>
              </div>
            </form>
          </div>

          <div class="class__board_text">
            <a href="register.php" class="board__text_link">還沒有帳號？立即註冊</a>
          </div>
        </div>

      </div>
    </div>
  </div>

</body>

</html>