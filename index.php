<?php
session_start();
if(isset($_SESSION['username'])){
    header('Location:main.php');
    exit();
}
?>

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
    <div class="login-top">
        <h1>MACアドレス管理システム</h1>
        <h2>ようこそ、ログインしてください。</h2>
        <form action="login.php" method="post">
            <div>
                <label for="username">ユーザー名</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password">
            </div>
            <button id="button" type="submit">ログイン</button>
        </form>
        <h2>新規登録はこちらから。</h2>
        <form action="signUp.php" method="post">
            <div>
                <label for="new-username">ユーザー名</label>
                <input type="text" name="username" id="new-username">
            </div>
            <div>
                <label for="new-password">パスワード</label>
                <input type="password" name="password" id="new-password">
            </div>
            <button id="button" type="submit">新規登録</button>
        </form>
    </div>
</body>
</html>