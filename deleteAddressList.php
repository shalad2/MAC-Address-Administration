<?php
session_start();
require_once('data/config.php');

// GETで受け取り
$mac_address = $_GET['mac_address'];

try{
    require_once('createTables.php');
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    // print "接続成功\n";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    $e->getMessage()."\n";
}

$head = <<< EOT
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>MACアドレス管理システム</title>
    <meta name="description" content="MACアドレス管理システムアドレス解除ページ">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress@3.0.0/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
 </head>
<body class="sub-page">
    <h1>MACアドレス管理システム</h1>
EOT;
$body = <<< EOT
<a href="main.php">戻る</a>
</body>
</html>
EOT;

// 有効なアドレスをリストから削除（無効化）
$isRegistered = $pdo->query("SELECT count(*) AS cnt FROM address_list WHERE mac_address='$mac_address'")
    ->fetch(PDO::FETCH_ASSOC);
if($mac_address == ''){
    print <<< EOT
    $head
    <p>MACアドレスが入力されていません。</p>
    $body
    EOT;
}elseif($isRegistered['cnt'] == 0){
    print <<< EOT
    $head
    <p>以下のMACアドレスは登録されていません。</p>
    <table>
        <tr>
            <td>MACアドレス</td>
            <td>$mac_address</td>
        </tr>
    </table>
    $body
    EOT;
}else{
    $pdo->query("UPDATE address_list SET valid=0 WHERE mac_address='$mac_address'");
    print <<< EOT
    $head
        <p>以下のMACアドレスを解除しました。</p>
        <table>
            <tr>
                <td>MACアドレス</td>
                <td id="address-red">$mac_address</td>
            </tr>
        </table>
    $body
    EOT;
}

?>