<?php 
session_start();
require('../db.php');
header('X-FRAME-OPTIONS:DENY');

if(isset($_SESSION['id']) && $_SESSION['time'] + 7200 > time()){
  $_SESSION['time'] = time();
  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
} else {
  header('Location: ../login.php');
  exit();
}

$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../main.css"/>
	<title>なんでも掲示板|新規スレッド作成</title>
</head>
<body>
<header>
	<div class="head">
		<h1>なんでも掲示板</h1>
    <button class="log_btn" onclick="location.href='../logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='../change/mypage.php'">会員情報変更</button>
  </div>
</header>
<div>
    <div class="thread">
      <a class="fle" href="threads.php">スレッド一覧</a>
      <a class="fle" href="../main.php?id=1">メインスレッド</a>
      <a class="fle active" href="thread/thread_new.php">スレッド作成</a>
    </div>

    <form class="search" action="../search.php" method="post">
        <input class="inp" type="text" name="search" placeholder="スレッド名、キーワードを入力">
        <input type="submit" value="スレッド検索">
    </form>
    <hr>
</div>
<div class="main">
  <h1 class="edi_title">新規スレッド作成</h1>

  <div class="content">
    <form action="thread_ok.php" method="post">
      <input type="hidden" name="token" value="<?php echo $token;?>">
      <div class="lab">
        <label class="lab1" for="ti">スレッドタイトル</label>
      </div>
      <input type="text" size="35" maxlength="255" name="title" id="ti">
      <div class="lab">
        <label class="lab1" for="con">スレッド最初の投稿</label>
      </div>
      <textarea name="content" cols="60" rows="7" id="con"></textarea>
      <div class="btns"><input class="okbtn" type="submit" value="送信する"/></p></div>
    </form>
  </div>

  <button class="post_btn medibtn" onclick="location.href='threads.php'">スレッド一覧へ</button>
  <button class="post_btn" onclick="location.href='../main.php?id=1'">メインスレッドへ</button>
</div> 
</body>
</html>
