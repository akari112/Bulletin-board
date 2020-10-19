<?php
session_start();
require('../db.php');
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

if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
	unset($_SESSION['token']);

	$type = $_SESSION['change'];

	if(!empty($_POST)){	
		if(!empty($type['name'])){
			$stmt = $db->prepare('UPDATE user SET name=? WHERE id=?');
			$stmt->execute(array(
				$_SESSION['change']['name'],
				$_SESSION['id']
			));
		}elseif(!empty($type['email'])){
			$stmt = $db->prepare('UPDATE user SET email=? WHERE id=?');
			$stmt->execute(array(
				$_SESSION['change']['email'],
				$_SESSION['id']
			));
		}elseif(!empty($type['pass'])){
			$stmt = $db->prepare('UPDATE user SET pass=? WHERE id=?');
			$stmt->execute(array(
				$_SESSION['change']['pass'],
				$_SESSION['id']
			));
		}elseif($type['picture']){
			$stmt = $db->prepare('UPDATE user SET picture=? WHERE id=?');
			$stmt->execute(array(
				$_SESSION['change']['picture'],
				$_SESSION['id']
			));
		}
		unset($_SESSION['change']);
	}

} else {
	header('Location: mypage.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>なんでも掲示板｜会員情報変更</title>
	<link rel="stylesheet" href="../main.css"/>
</head>
<body>
<header>
	<div class="head">
		<h1>なんでも掲示板</h1>
    <button class="log_btn" onclick="location.href='../logout.php'">ログアウト</button>
    <button class="cha_btn" onclick="location.href='mypage.php'">会員情報変更</button>
  </div>
</header>

<h1 class="edi_title">会員登録</h1>
</div>
<div class="edi_ok">
<p>変更が完了しました</p>
</div>
<button class="post_btn" onclick="location.href='../main.php?id=1'">メインスレッドに戻る</button>

</div>
</body>
</html>
