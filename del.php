<?php
session_start();
require('db.php');
header('X-FRAME-OPTIONS:DENY');

if(isset($_SESSION['id'])){
  if(!empty($_REQUEST['did']) || !empty($_REQUEST['tdid']) || !empty($_REQUEST['id'])){
    @$did = $_REQUEST['did'];
    @$tdid = $_REQUEST['tdid'];
    @$id = $_REQUEST['id'];

    if(isset($_REQUEST['did'])){
      $comments = $db->prepare('SELECT * FROM posts WHERE id=?');
      $comments->execute(array($did));
      $comment = $comments->fetch();
    
      if($comment['user_id'] === $_SESSION['id']){
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($did));
      }
      header("Location: main.php?id={$id}");
      exit();
    }elseif(isset($_REQUEST['tdid'])){

      $threads = $db->prepare('SELECT * FROM threads WHERE id=?');
      $threads->execute(array($tdid));
      $thread = $threads->fetch();
    
      if($thread['user'] === $_SESSION['id']){
        $tdel = $db->prepare('DELETE FROM threads WHERE id=?');
        $tdel->execute(array($tdid));
      }
      header("Location: thread/threads.php");
      exit();
    }
  }
}

?>