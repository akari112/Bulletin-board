<?php 
session_start();
require('../db.php');
//セッションidがあり、1時間以上経過無し
if(isset($_SESSION['id']) && $_SESSION['time'] + 7000 > time()){
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
// ページング
@$page = $_REQUEST['page'];
if($page == ''){
  $page = 1;
}
$page = max($page,1);
$count = $db->query('SELECT COUNT(*) AS cnt FROM threads');
$cnt = $count->fetch();
$maxpage = ceil($cnt['cnt']/5);
$page = min($page,$maxpage);
$start =($page -1) *5;

// 投稿表示
$threads = $db->prepare('SELECT * FROM threads ORDER BY created DESC LIMIT ?,5');
$threads->bindParam(1,$start,PDO::PARAM_INT);
$threads->execute();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../main.css"/>
	<title>みんなの掲示板|スレッド一覧</title>
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
    <a class="fle active" href="threads.php">スレッド一覧</a>
    <a class="fle" href="../main.php?id=1">メインスレッド</a>
    <a class="fle" href="thread_new.php">スレッド作成</a>
  </div>

  <form class="search" action="../search.php" method="post">
      <input class="inp" type="text" name="search" placeholder="スレッド名、キーワードを入力">
      <input type="submit" value="スレッド検索">
  </form>
  <hr>

  <div class="paging">
    <?php if($page > 1):?>
      <a href="threads.php?page=<?php echo $page-1?>">前のページへ</a>|
    <?php else:?>
      前のページへ |
    <?php endif;?>
      <?php echo $page;?>
    <?php if($page < $maxpage):?>|
      <a href="threads.php?page=<?php echo $page+1?>">次のページへ</a>
    <?php else:?>
      | 次のページへ
    <?php endif;?>
  </div>
  <hr>

  <div>
    <!-- スレッド表示の繰り返し -->
    <div class="searchs">
      <?php foreach($threads as $thread):?>
        <div class="msg">
         <div class="top1">
            <p><a href="../main.php?id=<?php echo $thread['id'];?>"><?php echo sanitize_br($thread['title']);?>
            <span>(<?php $id=$thread['id'];
            $stmt = $db->prepare('SELECT COUNT(*) FROM posts WHERE thread_id=?'); 
            $stmt->bindParam(1,$id,PDO::PARAM_INT); 
            $stmt->execute();
            echo $stmt->fetch(PDO::FETCH_COLUMN);
            ?>)</span></a></p>

            <?php if($_SESSION['id'] === $thread['user']):?>
              <p>[<a href="thread_del.php?tdid=<?php echo htmlspecialchars($thread['id'],ENT_QUOTES)?>"
              style="color: #F33;">削除</a>]</p>
            <?php endif;?>
          </div>
          <p  class="btm1"><?php echo htmlspecialchars($thread['created'],ENT_QUOTES);?></p>
        </div>
        <hr>
      <?php endforeach;?>
    </div>

    <div class="paging">
      <?php if($page > 1):?>
        <a href="threads.php?page=<?php echo $page-1?>">前のページへ</a>|
      <?php else:?>
        前のページへ |
      <?php endif;?>
        <?php echo $page;?>
      <?php if($page < $maxpage):?>|
        <a href="threads.php?page=<?php echo $page+1?>">次のページへ</a>
      <?php else:?>
        | 次のページへ
      <?php endif;?>
    </div>
  </div>
</div>
</body>
</html>
