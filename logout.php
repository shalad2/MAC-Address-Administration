<?php
session_start();
$output = "";
if(isset($_SESSION['username'])){
    $output = "ログアウトしました。";
}else{
    $output = "セッションがタイムアウトしました。";
}
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

@session_destroy();

print <<< EOT
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
    <p>$output</p>
    <a href="index.php">ログイン画面へ</a>
</body>
</html>
EOT

?>