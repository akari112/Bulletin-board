<?php
session_start();
require('db.php');

if(isset($_SESSION['id'])){
  $did = $_REQUEST['did'];
  $id = $_REQUEST['id'];

  $comments = $db->prepare('SELECT * FROM posts WHERE id=?');
  $comments->execute(array($did));
  $comment = $comments->fetch();

  if($comment['user_id'] === $_SESSION['id']){
    $del = $db->prepare('DELETE FROM posts WHERE id=?');
    $del->execute(array($did));
  }
}
header("Location: main.php?id={$id}");
exit();
?>