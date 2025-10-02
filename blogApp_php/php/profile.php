<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/db.php';

$user = current_user();
if (!$user) 
    {
    header('Location: /blogApp/php/login.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container">

  <h2>Profile</h2>

  <p>Username: <?= e($user['username']); ?></p>

  <p>Email: <?= e($user['email']); ?></p>

  <?php if ($user['bio']): ?>

    <p>Bio: <?= e($user['bio']); ?></p>

  <?php endif; ?>

  <?php if ($user['avatar_path']): ?>

    <img src="<?= e($user['avatar_path']); ?>" alt="Avatar" style="max-width:150px;">

  <?php endif; ?>

  <h3>Your Posts</h3>
  <?php
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");

    $stmt->execute([$user['id']]);

    $posts = $stmt->fetchAll();

    if (!$posts)
         {
        echo "<p>You have not written any posts yet.</p>";
    } else 
    {
        echo "<ul>";

        foreach ($posts as $p) 
            {
            echo "<li>";

            echo e($p['title']) . " (" . e($p['status']) . ")";

            echo " <a href=\"/blogApp/php/view_post.php?id=" . e($p['id']) . "\">View</a>";

            echo " <a href=\"/blogApp/php/edit_post.php?id=" . e($p['id']) . "\">Edit</a>";
            
            echo "</li>";
        }
        echo "</ul>";
    }
  ?>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
?>
