<?php 
session_start();
require('../db.php');
header('X-FRAME-OPTIONS:DENY');

if(isset($_SESSION['join'])){
	$name = $_SESSION['join']['name'];
	$email = $_SESSION['join']['email'];
	$pass = $_SESSION['join']['password'];
	$img = $_SESSION['join']['image'];

}else{
	header('Location: signup.php');
	exit();
}
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="new.css"/>
	<title>なんでも掲示板｜確認画面</title>
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
		<h1>確認画面</h1>
		<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
	</div>
	<div class="content">
		<form action="ok.php" method="post">
			<input type="hidden" name="token" value="<?php echo $token;?>">
			<input type="hidden" name="action" value="submit"/>

				<h4>ニックネーム</h4>
				<p><?php echo htmlspecialchars($name,ENT_QUOTES);?></p>

				<h4>メールアドレス</h4>
				<p><?php echo htmlspecialchars($email,ENT_QUOTES);?></p>

				<h4>パスワード</h4>
				<p>【表示されません】</p>

				<h4>プロフィール画像</h4>
				<?php if($img !== ''):?>
					<img src="../pictures/<?php echo htmlspecialchars($img,ENT_QUOTES);?>" alt="" width="110px" height="110px">
				<?php elseif($img === ''):?>
					<p>【画像を選択されていません】</p>
				<?php endif;?>
					
				<div class="btns"><input class="okbtn" type="submit" value="登録する"/></div>
		</form>
		<button class="rebtn" onclick="location.href='signup.php?action=rewrite'">書き直す</button>  
	</div>
</div>
</body>
</html>
