<?php
session_start();
header('X-FRAME-OPTIONS:DENY');
require('../db.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 7200 > time()){
  $_SESSION['time'] = time();

  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
} else {
  header('Location: ../login.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../main.css"/>
	<title>みんなの掲示板|会員情報変更</title>
</head>

<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
    <button class="log_btn" onclick="location.href='../logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='change/mypage.php'">会員情報変更</button>
  </div>
</header>

  <div>
    <h1 class="edi_title">会員情報変更</h1>
    <p>現在の会員情報を表示しています。<br>変更したい項目を選択してください。</p>
  </div>

  <div class="messages">
   <hr>
    <p><a href="change.php?id=name">ニックネーム</a></p>
    <p><?php echo htmlspecialchars($user['name'],ENT_QUOTES)?></p>
   <hr>
    <p><a href="change.php?id=email">メールアドレス</a></p>
    <p><?php echo htmlspecialchars($user['email'],ENT_QUOTES)?></p>
   <hr>
    <p><a href="change_pass.php">パスワード</a></p>
    <p>＊＊＊＊＊</p>
   <hr>
     <p><a href="change.php?id=picture">プロフィール画像</a></p>
    <img src="../pictures/<?php echo htmlspecialchars($user['picture'],ENT_QUOTES)?>" alt="" width="150px" height="150px"><br><br>
  </div><br>
  <button class="post_btn" onclick="location.href='../main.php'">一覧にもどる</button>

</body>
</html>
