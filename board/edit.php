<?php
session_start();
require('db.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 7200 > time()){
  $_SESSION['time'] = time();
  if(!isset($_REQUEST['eid']) || !isset($_REQUEST['id']) || empty($_REQUEST['id']) ||  empty($_REQUEST['eid']) ){
    header('Location: main.php?id=1');
    exit();
  }elseif(isset($_REQUEST['eid']) && isset($_REQUEST['id']) && !empty($_REQUEST['id']) && !empty($_REQUEST['eid']) ){
    $eid = $_REQUEST['eid'];
    $id = $_REQUEST['id'];
  
    $posts = $db->prepare('SELECT * FROM posts WHERE id=?');
    $posts->execute(array($eid));
    $post = $posts->fetch();
  
    if($post['user_id'] === $_SESSION['id']){
      if(!empty($_POST['comment']) && !empty($_POST['edit_id'])){
        $edit = $_POST['comment'];
        $eid = $_POST['edit_id'];
    
        $stmt = $db->prepare('UPDATE posts SET comment=? WHERE id=?');
        $stmt->execute(array($edit, $eid));
  
        header("Location: edit_ok.php?id={$id}&eid={$eid}");
        exit();
      }
    }
  }
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
	<title>みんなの掲示板|編集</title>
</head>

<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
    <button class="log_btn" onclick="location.href='logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='change/mypage.php'">会員情報変更</button>
  </div>
</header>

<div class="edit">
  <h1 class="edi_title">メッセージ編集画面</h1>
  <form action="" method="post">
    <div>
      <textarea name="comment" cols="65" rows="6"><?php echo htmlspecialchars($post['comment'],ENT_QUOTES)?></textarea>
      <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($_REQUEST['eid'],ENT_QUOTES);?>" />
    </div>
    <div>
      <p>
        <input class="post_btn" type="submit" value="変更する" />
      </p>
    </div>
  </form>
  <button class="post_btn" onclick="location.href='main.php?id=<?php echo $id?>'">戻る</button>
</div>
</body>
</html>
