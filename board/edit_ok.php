<?php
session_start();
require('db.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 7200 > time()){
  $_SESSION['time'] = time();
  $eid = $_REQUEST['eid'];
  $id = $_REQUEST['id'];

  $posts = $db->prepare('SELECT * FROM posts WHERE id=?');
  $posts->execute(array($eid));
} else {
  header('Location: login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="main.css"/>
	<title>みんなの掲示板|編集完了</title>
</head>

<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
    <button class="log_btn" onclick="location.href='logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='change/mypage.php'">会員情報変更</button>
  </div>
</header>

  <div>
  <h1 class="edi_title">編集完了</h1>
    <div class="edi_ok">
      <?php if($post = $posts->fetch()):?>
        <p><?php echo htmlspecialchars($post['comment'],ENT_QUOTES)?><br>
        に変更されました。</p>
      <?php endif;?>
    </div>
    <button class="post_btn" onclick="location.href='main.php?id=<?php echo $id?>'">スレッドにもどる</button>
  </div>
</body>
</html>
