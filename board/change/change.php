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

if(isset($_GET['id'])){
  $type = $_GET['id'];
  switch($type){
    case 'name':
      $aim = 'ニックネーム';
      break;
    case 'email':
      $aim = 'メールアドレス';
      break;
    case 'picture':
      $aim = 'プロフィール画像';
      break;
    }
}

if(!empty($_POST) ||!empty($_FILES)){
  if(!empty($_POST['name']) || !empty($_POST['email'])){
    $_SESSION['change'] = $_POST;

  } elseif(!empty($_FILES['picture'])){
    $picture = date('YmdHis').$_FILES['picture']['name'];
    move_uploaded_file($_FILES['picture']['tmp_name'],'../pictures/'.$picture);  
    $_SESSION['change']['picture'] = $picture;
  }
  header('Location: change_con.php');
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
	<title>みんなの掲示板|会員情報変更</title>
</head>
<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
    <button class="log_btn" onclick="location.href='../logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='mypage.php'">会員情報変更</button>
  </div>
</header>

<div>
  <h1 class="edi_title"><?php echo $aim;?>変更</h1>
</div>

<div class="edi_ok">
  <br><h3>現在の<?php echo $aim;?></h3>

  <?php if($type == 'name' || $type == 'email'):?>
    <p><?php echo htmlspecialchars($user[$type],ENT_QUOTES)?></p>
  <?php elseif($type == 'picture'):?>
    <img src="../pictures/<?php echo htmlspecialchars($user['picture'],ENT_QUOTES)?>" alt="" width="150px" height="150px">
  <?php endif;?>

  <form action="" method="post" enctype="multipart/form-data">
    <h3>変更したい<?php echo $aim;?></h3>

    <?php if($type == 'name'):?>
    <input id="mail" type="text" name="name" size="35" maxlength="255" value=""/><br><br>

    <?php elseif($type == 'email'):?>
    <input  id="mail" type="text" name="email" size="35" maxlength="255" value=""/><br><br>

    <?php elseif($type == 'picture'):?>
    <input id="img" type="file" name="picture" size="35" value="test"/><br><br>
    <?php endif;?>

    <input class="btn" type="submit" value="入力内容を確認する">
  </form><br>
</div><br>

  <button class="post_btn" onclick="location.href='mypage.php'">変更一覧にもどる</button>

</body>
</html>
