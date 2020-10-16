<?php 
session_start();
require('db.php');

//セッションidがあり、2時間以上経過無し
if(isset($_SESSION['id']) && $_SESSION['time'] + 7200 > time()){
  $_SESSION['time'] = time();
  @$id = $_REQUEST['id'];

  $threads = $db->prepare('SELECT * FROM threads WHERE id=?');
  $threads->execute(array($id));
  $thread = $threads->fetch();

  $users = $db->prepare('SELECT * FROM user WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
} else {
  header('Location: login.php');
  exit();
}
//投稿DB登録
if(!empty($_POST['comment']) && isset($_POST['comment'])){
  if($_POST['comment'] !== ''){
    $comment = $db->prepare('INSERT INTO posts SET user_id=?, comment=?, reply_id=?, thread_id=?, created=NOW()');
    $comment->execute(array(
      $user['id'],
      $_POST['comment'],
      $_POST['reply_id'],
      $id
    ));
    header("Location: main.php?id={$id}");
    exit();
  }
}elseif(isset($_POST['comment']) && $_POST['comment'] == ''){
  $error['comment'] = 'blank';
}
//改行
function sanitize_br($str){
  return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}
// idがなかった時
if($_REQUEST['id'] == '' || $_REQUEST['id'] == 0 || !isset($_REQUEST['id']) || empty($_REQUEST['id'])){
  $_REQUEST['id'] = 1;
  header('Location: main.php?id=1');
  exit();
}
// ページング 5件ごと
@$page = $_REQUEST['page'];
if($page == '' || !isset($page)){
  $page = 1;
}
$page = max($page,1);
$count = $db->prepare('SELECT COUNT(*) AS cnt FROM posts WHERE thread_id=?');
$count->execute(array($id));
$cnt = $count->fetch();
$maxpage = ceil($cnt['cnt']/5);
$page = min($page,$maxpage);
$start =($page -1) *5;

// 投稿表示
$posts = $db->prepare('SELECT u.name, u.picture, p.* FROM user u, posts p WHERE u.id=p.user_id AND thread_id=? ORDER BY p.created DESC LIMIT ?,5');
$posts->bindParam(1,$id,PDO::PARAM_INT);
$posts->bindParam(2,$start,PDO::PARAM_INT);
$posts->execute();
$comment = '';

//返信
if(isset($_REQUEST['res'])){
  $response = $db->prepare('SELECT u.name, u.id, p.* FROM user u, posts p WHERE u.id=p.user_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));
  $table = $response->fetch();
  if($table){
    $comment = '@'.$table['name']."\n".'>'.$table['comment']."\n";
  }else{
    $comment = '';
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="main.css"/>
	<title>みんなの掲示板</title>
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
      <a class="fle" href="thread/threads.php">スレッド一覧</a>
      <a class="fle active" href="main.php?id=1">メインスレッド</a>
      <a class="fle" href="thread/thread_new.php">スレッド作成</a>
    </div>

    <form class="search" action="search.php" method="post">
        <input class="inp" type="text" name="search" placeholder="スレッド名、キーワードを入力">
        <input type="submit" value="スレッド検索">
    </form>
    <hr>

    <div class="post">
      <form action="" method="post">
        <p><?php echo htmlspecialchars($user['name'], ENT_QUOTES);?>さん メッセージを投稿してください</p>
        <?php if(!empty($error['comment']) && $error['comment'] === 'blank'):?>
          <p class="error">*メッセージが書かれていません。</p>
        <?php endif;?>
        <textarea name="comment" cols="65" rows="6"><?php if($comment !== ''){echo htmlspecialchars($comment,ENT_QUOTES);}?></textarea>
        <input type="hidden" name="reply_id" value="<?php echo htmlspecialchars($_REQUEST['res'],ENT_QUOTES);?>" />
        <p><input class="post_btn" type="submit" value="投稿する" /></p>
      </form>

    </div>
    <hr>

    <div class="paging">
      <?php if($page > 1):?>
        <a href="main.php?id=<?php echo $id?>&page=<?php echo $page-1?>">前のページへ</a>|
      <?php else:?>
        前のページへ |
      <?php endif;?>
        <?php echo $page;?>
      <?php if($page < $maxpage):?>|
        <a href="main.php?id=<?php echo $id?>&page=<?php echo $page+1?>">次のページへ</a>
      <?php else:?>
        | 次のページへ
      <?php endif;?>
    </div>
    <hr>
    
    <div class="messages">
      <?php foreach($posts as $post):?>
        <div class="msg">
          <div class="top">
            <div class="m_img">
              <img class="p_img" src="pictures/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES)?>" 
              width="48" height="48" alt="<?php echo htmlspecialchars($post['name'],ENT_QUOTES)?>" />
            </div>
            <div class="side">
              <div class="side_top">
                <p class="name"><?php echo htmlspecialchars($post['name'],ENT_QUOTES)?></p>
                <p>[<a href="main.php?id=<?php echo $id?>&res=<?php echo htmlspecialchars($post['id'],ENT_QUOTES)?>">返信</a>]</p>
                <?php if($_SESSION['id'] === $post['user_id']):?>
                  <p>[<a href="del.php?id=<?php echo $id?>&did=<?php echo htmlspecialchars($post['id'],ENT_QUOTES)?>"
                  style="color: #F33;">削除</a>]</p>
                <?php endif;?>
                <?php if($_SESSION['id'] === $post['user_id']):?>
                  <p>[<a href="edit.php?id=<?php echo $id?>&eid=<?php echo htmlspecialchars($post['id'],ENT_QUOTES)?>">編集</a>]</p>
                <?php endif;?>
              </div>

              <div class="side_btm">
                <p class="day"><a href="view.php?id=<?php echo $id?>&vid=<?php echo htmlspecialchars($post['id'],ENT_QUOTES)?>"><?php echo htmlspecialchars($post['created'],ENT_QUOTES);?></a></p>
                <?php if($post['reply_id'] > 0):?>
                  <p><a href="view.php?id=<?php echo $id?>&vid=<?php echo htmlspecialchars($post['reply_id'],ENT_QUOTES)?>">
                    返信元</a></p>
                <?php endif;?>
              </div>
            </div>
          </div>
          <div class="btm">
            <p><?php echo sanitize_br($post['comment']);?></p>
          </div>
        </div>
        <hr>
      <?php endforeach;?>
    </div>

    <div class="paging">
      <?php if($page > 1):?>
        <a href="main.php?id=<?php echo $id?>&page=<?php echo $page-1?>">前のページへ</a>|
      <?php else:?>
        前のページへ |
      <?php endif;?>
        <?php echo $page;?>
      <?php if($page < $maxpage):?>|
        <a href="main.php?id=<?php echo $id?>&page=<?php echo $page+1?>">次のページへ</a>
      <?php else:?>
        | 次のページへ
      <?php endif;?>
    </div>

  </div>
</div>
</body>
</html>
