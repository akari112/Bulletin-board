<?php
session_start();
require('../db.php');
header('X-FRAME-OPTIONS:DENY');

$users = $db->prepare('SELECT * FROM user WHERE id=?');
$users->execute(array($_SESSION['id']));
$user = $users->fetch();

if(!empty($_POST)){
  if($user['pass'] !== sha1($_POST['pass'])){
    $error['password'] = 'miss';
  }elseif($_POST['npass1'] !== $_POST['npass2']){
    $error['password'] = 'not';
  }elseif($user['pass'] === sha1($_POST['pass']) && $_POST['npass1'] === $_POST['npass2']){
    $_SESSION['change']['pass'] = sha1($_POST['npass1']);
    header('Location: change_con.php');
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
	<title>なんでも掲示板｜会員情報変更</title>
</head>
<body>
<header>
	<div class="head">
		<h1>なんでも掲示板</h1>
    <button class="log_btn" onclick="location.href='../logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='mypage.php'">会員情報変更</button>
  </div>
</header>

  <div>
    <h1 class="edi_title">パスワード変更</h1>
  </div>
  <div class="messages">
    <form action="" method="post">
      <br>
      <div class="lab">
       <label class="lab1" for="pass">現在のパスワード</label>
      </div><br>
      <input type="password" name="pass" id="pass" size="10" maxlength="20"><br>
        <?php if(!empty($type['error']) && $error['password'] === 'miss'):?>
          <p>*パスワードが間違っています</p>
        <?php endif;?>
        <br><hr>
      <div class="lab">
        <label class="lab1" for="npass1">新しいパスワード</label>
      </div><br>
      <input type="password" name="npass1" id="npass1" size="10" maxlength="20"><br>
      <br><hr>
      <div class="lab">
        <label class="lab1" for="npass2">新しいパスワード(再入力)</label>
      </div><br>
      <input type="password" name="npass2" id="npass2" size="10" maxlength="20"><br>
        <?php if(!empty($type['error']) && $error['password'] === 'not'):?>
          <p>*パスワードが同じではありません</p>
        <?php endif;?>
        <br><hr><br>
      <button class="btn" type="submit">パスワードを変更する</button><br><br>
    </form>
  </div>

  <br><button class="post_btn" onclick="location.href='mypage.php'">変更一覧にもどる</button>

</body>
</html>
