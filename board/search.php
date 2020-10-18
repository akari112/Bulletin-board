<?php 
session_start();
require('db.php');
header('X-FRAME-OPTIONS:DENY');
//セッションidがあり、1時間以上経過無し
if(isset($_SESSION['id']) && $_SESSION['time'] + 7000 > time()){
  $_SESSION['time'] = time();

  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
} else {
  header('Location: login.php');
  exit();
}
//改行
function sanitize_br($str){
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}
//検索機能
if(!empty($_POST['search']) && isset($_POST['search'])){
  $searchs = $db->prepare("SELECT * FROM threads WHERE title LIKE ? ESCAPE '!' ORDER BY created");
  $searchs->bindValue(1, '%' . $_POST['search'] . '%', PDO::PARAM_STR);
  $searchs->execute();
  $search = $searchs->fetchAll();
} else {
  header('Location: main.php?id=1');
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
	<title>みんなの掲示板｜検索結果</title>
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
  <div>
    <div class="thread">
      <a class="fle active" href="thread/threads.php">スレッド一覧</a>
      <a class="fle" href="main.php?id=1">メインスレッド</a>
      <a class="fle" href="thread/thread_new.php">スレッド作成</a>
    </div>
    <hr>
    <div class="result">
      <h3>検索結果[<?php echo htmlspecialchars($_POST['search'],ENT_QUOTES)?>]</h3>
    </div>

    <!-- スレッド表示の繰り返し -->
    <?php if(isset($search) && !$search ==''):?>
      <div class="searchs">
        <?php foreach($search as $srh):?>
          <div class="msg">
            <div class="top1">
              <p><a href="main.php?id=<?php echo $srh['id'];?>"><?php echo sanitize_br($srh['title']);?>
              <!-- スレッド内の件数 -->
              <span>(<?php $id=$srh['id'];
              $stmt = $db->prepare('SELECT COUNT(*) FROM posts WHERE thread_id=?'); 
              $stmt->bindParam(1,$id,PDO::PARAM_INT); 
              $stmt->execute();
              echo $stmt->fetch(PDO::FETCH_COLUMN);
              ?>)</span></a></p>
              <?php if($_SESSION['id'] === $srh['user']):?>
                <p>[<a href="thread/thread_del.php?id=<?php echo htmlspecialchars($srh['id'],ENT_QUOTES)?>"
                style="color: #F33;">削除</a>]</p>
              <?php endif;?>
            </div>
            <p class="btm1"><?php echo htmlspecialchars($srh['created'],ENT_QUOTES);?></p>
          </div>
          <hr>
        <?php endforeach;?>
      </div>
      
       <button class="post_btn" onclick="location.href='thread/threads.php'">スレッド一覧へ</button>
       <button class="post_btn" onclick="location.href='main.php?id=1'">メインスレッドへ</button>
      
    <?php else:?>
      <h2>検索結果が見つかりません。</h2>
      <button class="post_btn" onclick="location.href='thread/threads.php'">スレッド一覧へ</button>
      <button class="post_btn" onclick="location.href='main.php?id=1'">メインスレッドへ</button>
    <?php endif;?>
  </div>
</div>
</body>
</html>
