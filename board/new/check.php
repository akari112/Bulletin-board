<?php 
session_start();
require('../db.php');

//セッションに内容がない場合
if(!isset($_SESSION['join'])){
	header('Location: signup.php');
	exit();
}

if(!empty($_POST)){	
	if(!empty($_POST['']))
	$stmt = $db->prepare('INSERT INTO user SET name=?, email=?, pass=?, picture=?, created=NOW()');
	$stmt->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
		$_SESSION['join']['image']
	));
	unset($_SESSION['join']);

	header('Location: ok.php');
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
	<title>新規登録｜確認画面</title>
</head>
<body>
<header>
	<div class="head">
		<p></p>
		<h1>みんなの掲示板</h1>
		<button class="log_btn" onclick="location.href='../login.php'">ログイン</button>
	</div>
</header>

<div class="main">
	<div class="title">
		<h1>確認画面</h1>
		<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
	</div>
	<div class="content">
		<form action="" method="post">
			<input type="hidden" name="action" value="submit"/>

				<h4>ニックネーム</h4>
				<p><?php echo htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES);?></p>

				<h4>メールアドレス</h4>
				<p><?php echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES);?></p>

				<h4>パスワード</h4>
				<p>【表示されません】</p>

				<h4>プロフィール画像</h4>
				<?php if($_SESSION['join']['image'] !== ''):?>
					<img src="../pictures/<?php echo htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES);?>" alt="" width="110px" height="110px">
				<?php endif;?>
					
				<div class="btns"><input class="okbtn" type="submit" value="登録する" /></div>
		</form>
		<button class="rebtn" onclick="location.href='signup.php?action=rewrite'">書き直す</button>  
	</div>
</div>
</body>
</html>
