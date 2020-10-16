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

//セッションに内容がない場合
if(!isset($_SESSION['change']) || empty($_SESSION['change'])){
	header('Location: change.php');
	exit();
}
$type = $_SESSION['change'];

if(!empty($_POST)){	
  if($type['name']){
    $stmt = $db->prepare('UPDATE user SET name=? WHERE id=?');
    $stmt->execute(array(
      $_SESSION['change']['name'],
      $_SESSION['id']
    ));
  }elseif($type['email']){
    $stmt = $db->prepare('UPDATE user SET email=? WHERE id=?');
    $stmt->execute(array(
      $_SESSION['change']['email'],
      $_SESSION['id']
    ));
  }elseif($type['pass']){
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
	header('Location: cha_ok.php');
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
	<title>みんなの掲示板｜会員情報変更確認</title>
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
  <h1 class="edi_title">登録内容変更確認</h1>
</div>
  <p>記入した内容を確認して、「変更する」ボタンをクリックしてください</p>

  <div class="edi_ok">
  <form action="" method="post">
    <input type="hidden" name="action" value="submit" />
      <br><h3>変更する内容</h3>

      <?php if(!empty($type['name'])):?>
       <p><?php echo htmlspecialchars($type['name'],ENT_QUOTES);?></p>
      <?php elseif(!empty($type['email'])):?>
        <p><?php echo htmlspecialchars($type['email'],ENT_QUOTES);?></p>
      <?php elseif(!empty($type['pass'])):?>
        <p>*****</p>
      <?php elseif(!empty($type['picture'])):?>
        <img src="../pictures/<?php echo htmlspecialchars($type['picture'],ENT_QUOTES);?>" alt="" width="150px" height="150px">
      <?php endif;?>
     
    <div class="btns"><input class="okbtn" type="submit" value="登録する"/></div><br>
  </form>
  </div><br>
  <button class="post_btn" onclick="location.href='../mypage.php'">戻る</button>

</body>
</html>
