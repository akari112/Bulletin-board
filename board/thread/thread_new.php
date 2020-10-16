<?php 
session_start();
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
//スレッドDB登録
if(!empty($_POST)){
  if($_POST['title'] !== '' && $_POST['content'] !== ''){
    $threads = $db->prepare('INSERT INTO threads SET user=?, title=?, created=NOW()');
    $threads->execute(array(
      $user['id'],
      $_POST['title']
    ));

    $ids = $db->prepare('SELECT * FROM threads WHERE user=? AND title=?');
    $ids->execute(array(
      $user['id'],
      $_POST['title']
    ));
    $id = $ids->fetch();
    $th_id = $id['id'];

    $posts = $db->prepare('INSERT INTO posts SET user_id=?, comment=?, thread_id=?, created=NOW()');
    $posts->execute(array(
      $user['id'],
      $_POST['content'],
      $th_id
    ));

    header("Location: thread_ok.php?id={$th_id}");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../main.css"/>
	<title>みんなの掲示板|新規スレッド作成</title>
</head>
<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
    <button class="log_btn" onclick="location.href='../logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='../change/mypage.php'">会員情報変更</button>
  </div>
</header>
<div class="main">
  <h1 class="edi_title">新規スレッド作成</h1>

  <div class="content">
    <form action="" method="post">
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

  <button class="post_btn" onclick="location.href='threads.php'">スレッド一覧へ</button>
  <button class="post_btn" onclick="location.href='../main.php?id=1'">メインスレッドへ</button>
</div> 
</body>
</html>
