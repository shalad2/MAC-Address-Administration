<?php

require_once('config.php');
session_start();

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
<h1>MACアドレス管理システム</h1>
<p>ユーザー名またはパスワードが間違っています。</p>
<a href="index.php">ログインページに戻る</a>
EOT;

// loginテーブル内にユーザー名が存在するかを確認
if(!isset($row['username'])){
    print $message;
    return false;
}

// パスワードを確認後、メインページへ移動
$hash_pass = password_hash($row['password'], PASSWORD_DEFAULT);
if(password_verify($_POST['password'], $hash_pass)){
    session_regenerate_id(true); // セクションIDを新しく生成し、置き換える
    $_SESSION['username'] = $row['username'];
    header('Location:main.php');
}else{
    print $message;
    return false;
}

?>