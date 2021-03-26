<?php
session_start();

require_once('data/config.php');

try{
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS address_list (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        position VARCHAR(50),
        email VARCHAR(255),
        device VARCHAR(255),
        mac_address VARCHAR(50),
        ip_address VARCHAR(50),
        registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");
    $list = $pdo->query('SELECT * FROM address_list');
    //$stmt->execute([$_POST['username']]);
    //$row = $stmt->fetch(PDO::FETCH_ASSOC); // 連想番号付きのレコードを取り出す
}catch(Exception $e){
    print $e->getMessage()."\n";
}

print <<< EOT
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>MACアドレス管理システム</title>
    <meta name="description" content="MACアドレス管理システムメインページ">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="main-page">
    <h1>MACアドレス管理システム</h1>
    <h2>ようこそ{$_SESSION['username']}さん</h2>
    <h3>登録リスト</h3>
EOT;
while($row = $list->fetch()){
    print $row[0];
    print $row[1];
    print $row[2];
    print $row[3];
    print $row[4];
    print $row[5];
    print $row[6];
    print $row[7];
    print "\n";
}

if($_SESSION['username'] == "admin"){
    print <<< EOT
    <p>MACアドレスの新規登録はこちら</p>
    <form action="addList.php" method="get">
        <div>
            <label for="name">使用者</label>
            <input type="text" name="name" id="name">
        </div>
        <div>
            <label for="position">役職</label>
            <input type="text" name="position" id="position">
        </div>
        <div>
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="device">機器名</label>
            <input type="text" name="device" id="device">
        </div>
        <div>
            <label for="mac_address">MACアドレス</label>
            <input type="text" name="mac_address" id="mac_address">
        </div>
        <div>
            <label for="ip_address">IPアドレス</label>
            <input type="text" name="ip_address" id="ip_address">
        </div>
        <button type="submit">新規登録</button>
    </form>
    EOT;
}

print <<< EOT
    <br>
    <a href="logout.php">ログアウト</a>
</body>
</html>
EOT;
?>