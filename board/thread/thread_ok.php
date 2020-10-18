<?php
require('../db.php');
header('X-FRAME-OPTIONS:DENY');
session_start();

//改行
function sanitize_br($str){
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

if(isset($_SESSION['id'])){
  $id = $_REQUEST['id'];

  $threads = $db->prepare('SELECT * FROM threads WHERE id=?');
  $threads->execute(array($id));
  $thread = $threads->fetch();

  $posts = $db->prepare('SELECT * FROM posts WHERE thread_id=?');
  $posts->execute(array($id));
  $post = $posts->fetch();
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

<h1 class="edi_title">新規スレッド作成完了</h1>
<div>
<p>新規スレッドが作成されました。</p>
<div class="edi_ok">
  <p><strong>タイトル</strong>　<?php echo htmlspecialchars($thread['title'], ENT_QUOTES)?></p>
  <p><strong>内容</strong><br><br><?php echo sanitize_br($post['comment']);?></p>
</div>

<button class="post_btn" onclick="location.href='threads.php'">スレッド一覧へ</button>
<button class="post_btn" onclick="location.href='thread_new.php'">スレッド作成画面へ戻る</button>

</div>

</div>
</body>
</html>
