<?php
session_start();
require_once('data/config.php');

try{
    require_once('createTables.php');
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $users_list = $pdo->query("SELECT * FROM users_list ORDER BY id");
}catch(Exception $e){
    $e->getMessage()."\n";
}

// メインページの表示
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
    <h3>現在登録済みのMACアドレス</h3>
    <table>
        <tr>
            <th>使用者</th>
            <th>役職</th>
            <th>メールアドレス</th>
            <th>機器名</th>
            <th>MACアドレス</th>
            <th>IPアドレス</th>
            <th>No.</th>
        </tr>
EOT;

// アドレスデータの表示
$i = 1;
while($user = $users_list->fetch(PDO::FETCH_ASSOC)){
    // 使用者の名前でアドレスリストを取得
    $name = $user['name'];
    $user_data = $pdo->query("SELECT * FROM address_list WHERE name='$name'");
    // 使用者のアドレス数を取得
    $user_data_num = $pdo->query("SELECT count(*) AS cnt FROM address_list WHERE name='$name'")
        ->fetch(PDO::FETCH_ASSOC);
    print <<< EOT
        <tr>
            <td rowspan={$user_data_num['cnt']}>{$user['name']}</td>
            <td rowspan={$user_data_num['cnt']}>{$user['position']}</td>
            <td rowspan={$user_data_num['cnt']}>{$user['email']}</td>
    EOT;
    
    $j = 0;
    while($row = $user_data->fetch(PDO::FETCH_ASSOC)){
        if($j == 0){
            print <<< EOT
                <td>{$row['device']}</td>
                <td id="mac-address">{$row['mac_address']}</td>
                <td>{$row['ip_address']}</td>
                <td>$i</td>
            </tr>
            EOT;
        }else{
            print <<< EOT
            <tr>
                <td>{$row['device']}</td>
                <td id="mac-address">{$row['mac_address']}</td>
                <td>{$row['ip_address']}</td>
                <td>$i</td>
            </tr>
            EOT;
        }
        $i++;
        $j++;
    }
}

print <<< EOT
</table>
EOT;

// 管理者であれば編集の表示
if(in_array($_SESSION['username'], ADMIN)){
    print <<< EOT
    <div class="registration">
        <div>
        <h2>MACアドレスの新規登録はこちら</h2>
        <form action="addAddressList.php" method="get">
            <span>
                <label for="name">使用者</label>
                <input type="text" name="name" id="name">
            </span>
            <span>
                <label for="device">機器名</label>
                <input type="text" name="device" id="device">
            </span>
            <span>
                <label for="mac_address">MACアドレス</label>
                <input type="text" name="mac_address" id="mac_address">
            </span>
            <span>
                <label for="ip_address">IPアドレス</label>
                <input type="text" name="ip_address" id="ip_address">
            </span>
            <button id="button" type="submit">新規登録</button>
        </form>
        </div>
        <div>
        <h2>使用者の新規登録はこちら</h2>
        <form action="addUsersList.php" method="get">
            <span>
                <label for="name">使用者</label>
                <input type="text" name="name" id="name">
            </span>
            <span>
                <label for="position">役職</label>
                <input type="text" name="position" id="position">
            </span>
            <span>
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email">
            </span>
            <button id="button" type="submit">新規登録</button>
        </form>
        </div>
    </div>
    EOT;
}

print <<< EOT
    <a href="logout.php">ログアウト</a>
</body>
</html>
EOT;

?>