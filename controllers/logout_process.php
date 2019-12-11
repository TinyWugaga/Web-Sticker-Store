<?php
    session_start();
    session_unset();
    header("Location:../stickerPage.php");
    die();
?>