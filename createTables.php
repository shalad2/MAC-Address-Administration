<?php
session_start();
require_once('data/config.php');

try{
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 使用者情報テーブルの作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS users_list (
        id INT(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        position VARCHAR(50),
        email VARCHAR(255),
        registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");
    // アドレス情報テーブルの作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS address_list (
        id INT(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        device VARCHAR(255),
        mac_address VARCHAR(50),
        ip_address VARCHAR(50),
        registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        valid BIT(1) NOT NULL DEFAULT b'1'
    )");
}catch(Exception $e){
    print $e->getMessage()."\n";
}

?>