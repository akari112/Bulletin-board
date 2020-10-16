<?php
session_start();
require('../db.php');

if(!empty($_POST)){
	if($_POST['name'] == ''){
		$error['name'] = 'blank';
	}
	if($_POST['email'] == ''){
		$error['email'] = 'blank';
	}
	if(strlen($_POST['password']) < 4){
		$error['password'] = 'length';
	}
	if($_POST['password'] == ''){
	$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		$ext = substr($fileName, -3);
		if($ext != 'png' && $ext !='jpeg'){
			$error['image'] = 'type';
		}
	}
	//重複チェック
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM user WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cnt'] > 0){
			$error['email'] = 'duplicate';
		}
	}
	if(empty($error)){
		$image = date('YmdHis').$_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'],'../pictures/'.$image);

		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}

if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
	$_POST = $_SESSION['join'];
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="new.css"/>
	<title>新規登録</title>
</head>
<body>
<header>
	<div class="head">
		<h1>みんなの掲示板</h1>
		<button class="log_btn" onclick="location.href='../login.php'">ログイン</button>
	</div>
</header>

<div class="main">
<div class="title">
	<h1>新規登録</h1>
	<p>以下の項目を入力して会員登録してください。<br>全て必須項目となっています。</p>
</div>

<div class="content">
	<form action="" method="post" enctype="multipart/form-data">
			<div  class="inp">
				<div class="lab">
					<label class="lab1" for="name">ニックネーム</label>
				</div>
					<input id="name" type="text" name="name" size="35" maxlength="255" value="<?php if($_POST){echo htmlspecialchars($_POST['name'],ENT_QUOTES);}?>" />
						<?php if(!empty($error['name']) && $error['name'] === 'blank'):?>
							<p class="error">*ニックネームを入力してください</p>
						<?php endif;?>
			</div>
			<div>
				<div class="lab">
					<label class="lab1" for="email">メールアドレス</label>
				</div>
					<input id="email" type="text" name="email" size="35" maxlength="255" value="<?php if($_POST){echo htmlspecialchars($_POST['email'],ENT_QUOTES);}?>" />
						<?php if(!empty($error['email']) && $error['email'] === 'blank'):?>
							<p class="error">*emailを入力してください</p>
						<?php endif;?>
						<?php if(!empty($error['email']) && $error['email'] === 'duplicate'):?>
							<p class="error">*指定されたメールアドレスは既に使用されています。</p>
						<?php endif;?>
			</div>
			<div>
				<div class="lab">
					<label class="lab1" for="pass">パスワード(4文字以上)</label>
				</div>
					<input id="pass" type="password" name="password" size="10" maxlength="20" value="<?php if($_POST){echo htmlspecialchars($_POST['password'],ENT_QUOTES);}?>" />
						<?php if(!empty($error['password']) && $error['password'] === 'length'):?>
							<p class="error">*パスワードは４文字以上で入力してください</p>
						<?php endif;?>
						<?php if(!empty($error['password']) && $error['password'] === 'blank'):?>
							<p class="error">*パスワードを入力してください</p>
						<?php endif;?>
			</div>
			<div>
				<div class="lab">
					<label class="lab1" for="img">プロフィール画像</label>
				</div>
					<input id="img" type="file" name="image" size="35" value="test"/>
						<?php if(!empty($error['image']) && $error['image'] === 'type'):?>
							<p class="error">＊写真は「png」か「jpeg」の画像に変更してください</p>
						<?php endif;?>
						<?php if(!empty($error)):?>
							<p class="error">*恐れ入りますが、もう一度画像を指定してください</p>
						<?php endif;?>
			</div>
			<div><input class="btn" type="submit" value="入力内容を確認する"/></div>
	</form>
</div>
</div>
</body>
</html>
