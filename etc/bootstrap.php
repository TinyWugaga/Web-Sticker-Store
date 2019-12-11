<?php

session_start();

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/sql.php';

date_default_timezone_set('Asia/Taipei');

//萬能偵錯用函式
function dd($expression) {
    echo '<pre>', var_export($expression, true), die;
}