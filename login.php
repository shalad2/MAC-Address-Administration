<?php
session_start();
require_once('data/config.php');

// DB内のloginテーブルからユーザー名を検索
try{
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    //print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare('SELECT * FROM login where username = ?');
    $stmt->execute([$_POST['username']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC); // 連想番号付きのレコードを取り出す
}catch(Exception $e){
    $e->getMessage()."\n";
}

// ログインに失敗した時のメッセージ
$message = <<< EOT
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="description" content="MACアドレス管理システムログインページ">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>MACアドレス管理システム</h1>
    <p>ユーザー名またはパスワードが間違っています。</p>
    <a href="index.php">ログインページに戻る</a>
</body>
</html>
EOT;

// loginテーブル内にユーザー名が存在するかを確認
if(!isset($row['username'])){
    print $message;
    return false;
}

// パスワードを確認後、メインページへ移動
if(password_verify($_POST['password'], $row['password'])){
    //print "before: ".session_id()."\n";
    session_regenerate_id(TRUE); // セクションIDを新しく生成し、置き換える
    $_SESSION['username'] = $row['username'];
    session_write_close();
    //print "after: ".session_id();
    header('Location:main.php');
    exit();
}else{
    print $message;
    return false;
}

?>