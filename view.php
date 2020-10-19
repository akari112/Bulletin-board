<?php
session_start();
require('db.php');
header('X-FRAME-OPTIONS:DENY');

if(empty($_REQUEST['vid']) || empty($_REQUEST['id'])){
 header('Location: main.php?id=1');
 exit();
}

$id = $_REQUEST['id'];

$posts = $db->prepare('SELECT u.id, u.name, u.picture, p.* FROM user u, posts p WHERE u.id=p.user_id AND p.id=?');
$posts->execute(array($_REQUEST['vid']));

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="main.css"/>
	<title>なんでも掲示板|投稿</title>
</head>

<body>
<header>
	<div class="head">
		<h1>なんでも掲示板</h1>
    <button class="log_btn" onclick="location.href='logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='change/mypage.php'">会員情報変更</button>
  </div>
</header>

  <div class="content">
    <?php if($post = $posts->fetch()):?>
      <div class="view">
        <div class="v_top">
          <div class="v_img">
            <img src="pictures/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES)?> " width="90px" height="90px"/>
          </div>
          <div class="v_pro">
            <p class="name"><?php echo htmlspecialchars($post['name'],ENT_QUOTES)?></p>
            <p class="day"><?php echo htmlspecialchars($post['created'],ENT_QUOTES)?></p>
          </div>
        </div>
        <div class="edi_ok">
          <p><?php echo htmlspecialchars($post['comment'],ENT_QUOTES)?></p>
        </div>
      </div>
    <?php else:?>
      <p>その投稿は削除されたか、URLが間違えています</p>
    <?php endif;?>
  </div>

  <button class="post_btn" onclick="location.href='main.php?id=<?php echo $id?>'">一覧にもどる</button>

</body>
</html>
