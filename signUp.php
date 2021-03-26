<?php
session_start();
require_once('data/config.php');

// DBへ接続、ログインテーブルがない場合は作成
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // echo "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS login (
        username VARCHAR(255) UNIQUE,
        password VARCHAR(255),
        created_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    print $e->getMessage()."\n";
}

$head = <<< EOT
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <meta name="description" content="MACアドレス管理システム新規登録ページ">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>MACアドレス管理システム</h1>
EOT;

$body =  <<< EOT
<a href="index.php">ログインページに戻る</a>
</body>
</html>
EOT;

// ユーザー名の正規表現
if(empty($_POST['username'])){
    print <<< EOT
    $head
    <p>ユーザー名が入力されていません。</p>
    $body
    EOT;
    return false;
}elseif(preg_match('/[^a-z\d]/i', $_POST['username'])){
    print <<< EOT
    $head
    <p>ユーザー名は半角英数字のみで設定してください。</p>
    $body
    EOT;
    return false;
}else{
    $username = $_POST['username'];
}

// パスワードの正規表現
if(empty($_POST['password'])){
    print <<< EOT
    $head
    <p>パスワードが入力されていません。</p>
    $body
    EOT;
    return false;
}elseif(preg_match('/[^a-z\d]/i', $_POST['password'])){
    print <<< EOT
    $head
    <p>パスワードは半角英数字のみで設定してください。</p>
    $body
    EOT;
    return false;
}else{
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
}

// 新規ユーザー登録処理
try {
    $stmt = $pdo->prepare("INSERT INTO login (username, password) VALUES(?, ?)");
    $stmt->execute([$username, $password]);
    print <<< EOT
    $head
    <p>登録が完了しました。ログインページからログインしてください。</p>
    $body
    EOT;
}catch(Exception $e){
    print <<< EOT
    $head
        <p>このユーザーは既に登録されています。</p>
    $body
    EOT;
}