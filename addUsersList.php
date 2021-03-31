<?php
session_start();
require_once('data/config.php');

// GETで受け取り
$name = $_GET['name'];
$position = $_GET['position'];
$email = $_GET['email'];

try{
    require_once('createTables.php');
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 使用者情報がなければ登録
    $pdo->query("INSERT INTO users_list VALUES (
        default,
        '$name',
        '$position',
        '$email',
        default
    )");
}catch(Exception $e){
    $e->getMessage()."\n";
}

print <<< EOT
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>MACアドレス管理システム</title>
    <meta name="description" content="MACアドレス管理システム新規使用者追加ページ">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="main-page">
    <h1>MACアドレス管理システム</h1>
    <p>以下の内容で使用者を追加しました。</p>
    <table>
        <tr>
            <td>使用者</td>
            <td>$name</td>
        </tr>
        <tr>
            <td>役職</td>
            <td>$position</td>
        </tr>
        <tr>
            <td>メールアドレス</td>
            <td>$email</td>
        </tr>
    </table>
    <a href="main.php">戻る</a>
</body>
</html>
EOT;

?>