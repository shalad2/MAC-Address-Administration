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
    <meta name="description" content="MACアドレス管理システム履歴ページ">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="main-page">
    <h1>MACアドレス管理システム</h1>
    <h3>MACアドレスの登録履歴</h3>
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
// i : 通し番号
// j : 使用者の1番目のアドレスを判定
$i = 1;
while($user = $users_list->fetch(PDO::FETCH_ASSOC)){
    // 使用者の名前でアドレスリストを取得
    $name = $user['name'];
    $user_data = $pdo->query("SELECT * FROM address_list WHERE name='$name'");
    // 使用者のアドレス数を取得
    $user_data_num = $pdo->query("SELECT count(*) AS cnt FROM address_list WHERE name='$name'")
        ->fetch(PDO::FETCH_ASSOC);
    // 有効なアドレスがあれば表示
    if($user_data_num['cnt'] != 0){
        print <<< EOT
            <tr>
                <td rowspan={$user_data_num['cnt']}>{$user['name']}</td>
                <td rowspan={$user_data_num['cnt']}>{$user['position']}</td>
                <td rowspan={$user_data_num['cnt']}>{$user['email']}</td>
        EOT;
        
        $j = 0;
        while($row = $user_data->fetch(PDO::FETCH_ASSOC)){
            if($j != 0){
                print "<tr>";
            }
            print "<td>{$row['device']}</td>";
            if($row['valid'] == 1){
                print "<td id='mac-address'>{$row['mac_address']}</td>";
            }else{
                print "<td id='address-red'>{$row['mac_address']}</td>";
            }
            print "<td>{$row['ip_address']}</td>";
            print "<td>$i</td>";
            if($j != 0){
                print "</tr>";
            }
            $i++;
            $j++;
        }
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
        <div>
        <h2>MACアドレス履歴の削除はこちら</h2>
        <form action="deleteHistoryList.php" method="get">
            <span>
            <label for="mac_address">MACアドレス</label>
            <input type="text" name="mac_address" id="mac_address">
            </span>
            <button id="red-button" type="submit">登録解除</button>
        </form>
        </div>
        <div>
        <a href="main.php">戻る</a>
        <a href="logout.php">ログアウト</a>
        </div> 
    </div>
    EOT;
}

print <<< EOT
</body>
</html>
EOT;

?>