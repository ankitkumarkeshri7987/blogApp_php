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

    <h2>Dashboard</h2>

    <p>Welcome, <?= e($user['username']); ?>!</p>

    
    <p>Your Posts:</p>

    <?php

    $stmt = $pdo->prepare("SELECT id, title, status, published_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    
    $stmt->execute([$user['id']]);

    $myPosts = $stmt->fetchAll();

    ?>
    
    <?php if (count($myPosts) == 0): ?>

        <p>You have not created any posts yet.</p>

    <?php else: ?>

        <ul>

        <?php foreach ($myPosts as $post): ?>
            <li>
                <?= e($post['title']); ?> — <?= e($post['status']); ?>
                (<?php if ($post['published_at']) echo e($post['published_at']); ?>)
                | <a href="/blogApp/php/edit_post.php?id=<?= e($post['id']); ?>">Edit</a>
                | <a href="/blogApp/php/view_post.php?id=<?= e($post['id']); ?>">View</a>
            </li>

        <?php endforeach; ?>

        </ul>
    <?php endif; ?>
    
    <p><a href="/blogApp/php/create_post.php">Create a new post</a></p>

</div>

<?php

include __DIR__ . '/../includes/footer.php';
?>
