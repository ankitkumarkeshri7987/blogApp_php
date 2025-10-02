<?php
require_once __DIR__.'/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$user = current_user();

if(!$user)
    {
         flash('error','Login to like');
          header('Location: /blogApp/php/login.php'); exit; 
        }
if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
         header('Location: /blogApp/index.php');
          exit;
         }
if(!verify_csrf($_POST['csrf'] ?? ''))
    { flash('error','Invalid CSRF');
         header('Location: /blogApp/index.php'); 
         exit; }

$post_id = intval($_POST['post_id'] ?? 0);

if(!$post_id)
    { header('Location: /blogApp/index.php'); exit; }



$del = $pdo->prepare("DELETE FROM likes WHERE post_id=? AND user_id=?");

$del->execute([$post_id, $user['id']]);

if($del->rowCount() === 0)
    {

    $ins = $pdo->prepare("INSERT IGNORE INTO likes (post_id,user_id) VALUES (?,?)");

    $ins->execute([$post_id,$user['id']]);
}

header("Location: /blogApp/php/view_post.php?id={$post_id}");
exit;
