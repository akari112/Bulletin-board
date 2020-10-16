<?php
 ini_set("display_errors", "On");
// データベース接続
$dsn = 'mysql:dbname=tech;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

try {
  $db = new PDO($dsn, $user, $password);
  //ユーザーテーブル
  $sql = "CREATE TABLE IF NOT EXISTS user"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "name VARCHAR(200),"
  . "email VARCHAR(200),"
  . "pass VARCHAR(100),"
  . "picture VARCHAR(200),"
  . "created DATETIME,"
  . "modified TIMESTAMP "
  .");";
  $stmt = $db->query($sql);

  //投稿用テーブル
  $sql = "CREATE TABLE IF NOT EXISTS posts"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "user_id INT(11),"
  . "comment TEXT,"
  . "reply_id INT(11),"
  . "thread_id INT(11),"
  . "created DATETIME,"
  . "modified TIMESTAMP"
  .");";
  $stmt = $db->query($sql);

  //スレッドテーブル
  $sql = "CREATE TABLE IF NOT EXISTS threads"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "user INT(11),"
  . "title TEXT,"
  . "created DATETIME,"
  . "modified TIMESTAMP"
  .");";
  $stmt = $db->query($sql);

  $stmt = $db->query('SELECT * FROM threads');
  $cnt = $stmt->fetch();
  
  if($cnt === false){
    $title = 'main';
    $stmt = $db->prepare('INSERT INTO threads SET title=?, created=NOW()');
    $stmt->execute(array($title));
  }

}catch(PDOException $e){
  echo 'DB接続エラー:'. $e->getMessage();
}
?>