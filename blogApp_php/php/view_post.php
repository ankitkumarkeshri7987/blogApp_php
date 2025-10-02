<?php
require_once __DIR__.'/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$id = $_GET['id'] ?? null;

if(!$id) 
    {
         header('Location: /blogApp/index.php'); 
         exit;
         }

$stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ? AND p.status = 'published'");

$stmt->execute([$id]);

$post = $stmt->fetch();

if(!$post){ flash('error','Post not found'); header('Location: /blogApp/index.php'); exit; }


$cmts = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id=u.id WHERE c.post_id=? ORDER BY c.created_at ASC");
$cmts->execute([$id]);

$comments = $cmts->fetchAll();


include __DIR__ . '/../includes/header.php'; ?>

<article class="post">

  <h1><?=e($post['title'])?></h1>

  <p class="meta">By <?=e($post['username'])?> • <?=e($post['published_at'])?></p>

  <?php if($post['thumbnail_path']): ?><img src="<?=e($post['thumbnail_path'])?>" class="thumb"><?php endif; ?>

  <div class="content"><?= $post['content']  ?></div>

  <div class="engagement">

    <?php if(current_user()): ?>

      <form action="/blogApp/php/like.php" method="post" style="display:inline;">

        <input type="hidden" name="csrf" value="<?=csrf_token()?>">

        <input type="hidden" name="post_id" value="<?=e($post['id'])?>">

        <button type="submit">Like</button>

      </form>
      <form action="/blogApp/php/comment.php" method="post" style="margin-top:10px;">

        <input type="hidden" name="csrf" value="<?=csrf_token()?>">

        <input type="hidden" name="post_id" value="<?=e($post['id'])?>">

        <textarea name="comment_text" required placeholder="Add a comment"></textarea>

        <button type="submit">Post Comment</button>

      </form>
    <?php else: ?>

      <p><a href="/blogApp/php/login.php">Login</a> to like or comment.</p>
    <?php endif; ?>
  </div>

  <section class="comments">

    <h3>Comments</h3>

    <?php foreach($comments as $c): ?>
      <div class="comment"><strong><?=e($c['username'])?></strong> <small><?=e($c['created_at'])?></small>

        <p><?=e($c['comment_text'])?></p>
        
      </div>
    <?php endforeach; ?>
  </section>
</article>
<?php include __DIR__ . '/../includes/footer.php'; ?>
