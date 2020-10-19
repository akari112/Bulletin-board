<?php
require('db.php');
session_start();
header('X-FRAME-OPTIONS:DENY');

if(!empty($_COOKIE['email']) && $_COOKIE['email'] !== ''){
  $email = $_COOKIE['email'];
}

if(!empty($_POST)){
  $email = $_POST['email'];
  
  if($_POST['email'] !== '' && $_POST['password'] !== ''){
    $login = $db->prepare('SELECT * FROM user WHERE email=? AND pass=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();

    if($member){
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      if($_POST['save'] === 'on'){
        setcookie('email', $_POST['email'], time()+60*60*24*14);
      }
      header('Location: main.php?id=1');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } elseif($_POST['email'] === '') {
    $error['email'] = 'blank';
  } elseif($_POST['password'] === '') {
    $error['pass'] = 'blank';
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <link rel="stylesheet" href="main.css"/>
  <title>なんでも掲示板｜ログイン</title>
</head>
<body>

<div class="head1">
  <h1>なんでも掲示板</h1>
  <p>日々の中であったささいな出来事、誰かに聞いてほしいこと</p><p>相談したいこと、趣味、仕事のこと</p>
  <p>どんなことでもこの掲示板で話してみませんか？</p>
</div>
<div class="form_content">
  <form action="" method="post">
    <div>
      <div class="lab">
        <label class="lab1" for="mail">メールアドレス</label>
      </div>
      <input id="mail" type="text" name="email" maxlength="255" value="<?php if(!empty($email)){echo htmlspecialchars($email,ENT_QUOTES);} ?>" />
      <?php if(!empty($error['email']) && $error['email'] === 'blank'):?>
          <p class="error">*メールアドレスをご記入ください</p>
      <?php endif;?>

      <div class="lab">
        <label class="lab1" for="pass">パスワード</label>
      </div>
      <input id="pass" type="password" name="password" maxlength="255" value=""/>
      <?php if(!empty($error['pass']) && $error['pass'] === 'blank'):?>
        <p class="error">*パスワードをご記入ください</p>
      <?php endif;?>
      <?php if(!empty($error['login']) && $error['login'] === 'failed'):?>
        <p class="error">*ログインに失敗しました。メールアドレスとパスワードが間違っています。</p>
      <?php endif;?>          

      <div class="save">
        <input id="save" type="checkbox" name="save" value="on">
        <label for="save">次回からは自動的にログインする</label>
      </div>
    </div>

    <div>
      <input class="btn" type="submit" value="ログインする" />
    </div>
  </form>

  <div id="lead">
    <p>----------新規登録がまだの方はこちらから----------</p>
    <a href="new/signup.php"><button class="btn">新規登録をする</button></a>
  </div>
</div>

</body>
</html>
