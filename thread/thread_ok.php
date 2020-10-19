<?php
require('../db.php');
session_start();
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

//改行
function sanitize_br($str){
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
	unset($_SESSION['token']);

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
    }
  }
} else {
	header('Location: threads.php');
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
	<title>みんなの掲示板｜スレッド作成</title>
</head>
<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
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

<h1 class="edi_title">新規スレッド作成完了</h1>
<div>
<p>新規スレッドが作成されました。</p>
<div class="edi_ok">
  <p><strong>タイトル</strong>　<?php echo htmlspecialchars($_POST['title'], ENT_QUOTES)?></p>
  <p><strong>内容</strong><br><br><?php echo sanitize_br($_POST['content']);?></p>
</div>

<button class="post_btn medibtn" onclick="location.href='threads.php'">スレッド一覧へ</button>
<button class="post_btn" onclick="location.href='thread_new.php'">スレッド作成画面へ戻る</button>

</div>

</div>
</body>
</html>
