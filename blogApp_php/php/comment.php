<?php

require_once __DIR__.'/db.php';

require_once __DIR__ . '/../includes/helpers.php';

$user = current_user();

if(!$user)
    { 
        flash('error','Login to comment');
         header('Location: /blogApp/php/login.php'); 
         exit;
        }
if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
         header('Location: /blogApp/index.php'); 
         exit; 
        }
if(!verify_csrf($_POST['csrf'] ?? ''))
    { 
        flash('error','Invalid CSRF');
         header('Location: /blog-platform/index.php'); 
         exit; 
        }

$post_id = intval($_POST['post_id'] ?? 0);

$text = trim($_POST['comment_text'] ?? '');

$parent = !empty($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;

if(!$post_id || !$text)
    { flash('error','Invalid input');
         header("Location: /blogApp/php/view_post.php?id={$post_id}");
          exit; 
        }

$stmt = $pdo->prepare("INSERT INTO comments (post_id,user_id,parent_comment_id,comment_text,status) VALUES (?,?,?,?, 'approved')");

$stmt->execute([$post_id,$user['id'],$parent,$text]);

flash('success','Comment posted');

header("Location: /blogApp/php/view_post.php?id={$post_id}");

exit;
