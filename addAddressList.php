<?php
session_start();
require_once('data/config.php');

// GETで受け取り
$name = $_GET['name'];
$device = $_GET['device'];
$mac_address = $_GET['mac_address'];
$ip_address = $_GET['ip_address'];

try{
    require_once('createTables.php');
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    $e->getMessage()."\n";
}

// 使用者に登録されていればアドレスを追加
$isRegistered = $pdo->query("SELECT count(*) AS cnt FROM users_list WHERE name='$name'")
    ->fetch(PDO::FETCH_ASSOC);
if($isRegistered['cnt'] != 0 || $name == ''){
    // アドレス情報の追加
    $pdo->query("INSERT INTO address_list VALUES (
        default,
        '$name',
        '$device',
        '$mac_address',
        '$ip_address',
        default,
        default
    )");
    print <<< EOT
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>MACアドレス管理システム</title>
        <meta name="description" content="MACアドレス管理システム新規アドレス追加ページ">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <!-- CSS -->
        <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body class="sub-page">
        <h1>MACアドレス管理システム</h1>
        <p>以下の内容でアドレスを追加しました。</p>
        <table>
            <tr>
                <td>使用者</td>
                <td>$name</td>
            </tr>
            <tr>
                <td>機器名</td>
                <td>$device</td>
            </tr>
            <tr>
                <td>MACアドレス</td>
                <td>$mac_address</td>
            </tr>
            <tr>
                <td>IPアドレス</td>
                <td>$ip_address</td>
            </tr>
        </table>
        <a href="main.php">戻る</a>
    </body>
    </html>
    EOT;
}else{
    print <<< EOT
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>MACアドレス管理システム</title>
        <meta name="description" content="MACアドレス管理システム新規アドレス追加ページ">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <!-- CSS -->
        <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body class="main-page">
        <h1>MACアドレス管理システム</h1>
        <p>この使用者はまだ登録されていません。使用者登録を行ってください。</p>
        <a href="main.php">戻る</a>
    </body>
    </html>
    EOT;
}

?>