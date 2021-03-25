<?php

session_start();
require_once('config.php');
//データベースへ接続、テーブルがない場合は作成
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    echo "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists userDeta(
        id int not null auto_increment primary key,
        email varchar(255),
        password varchar(255),
        created timestamp not null default current_timestamp
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}