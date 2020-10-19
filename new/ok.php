<?php
session_start();
require('../db.php');
header('X-FRAME-OPTIONS:DENY');

if(isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] === $_POST['token']){
	unset($_SESSION['token']);

	$name = $_SESSION['join']['name'];
	$email = $_SESSION['join']['email'];
	$pass = sha1($_SESSION['join']['password']);
	$img = $_SESSION['join']['image'];

	$stmt = $db->prepare('INSERT INTO user SET name=?, email=?, pass=?, picture=?, created=NOW()');
	$stmt->execute(array(
		$name,
		$email,
		$pass,
		$img
	));
	unset($_SESSION['join']);

} else {
	header('Location: signup.php');
	exit();
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="new.css"/>
	<title>なんでも掲示板|会員登録</title>
</head>
<body>
<header>
	<div class="head">
		<h1>なんでも掲示板</h1>
		<button class="log_btn" onclick="location.href='../login.php'">ログイン</button>
	</div>
</header>

<div class="main">
	<div class="title">
		<h1>新規登録|ご登録完了</h1>
	</div>

	<div>
	<p>会員登録が完了しました</p>
	<p>ご登録ありがとうございます。</p>
	<button class="btn gobtn" onclick="location.href='../login.php'">ログインする</button>
	</div>

</div>
</body>
</html>
