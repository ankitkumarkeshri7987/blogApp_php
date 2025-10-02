<?php
require_once __DIR__.'/php/db.php';
require_once __DIR__.'/includes/helpers.php';
$stmt = $pdo->query("SELECT p.id,p.title,p.excerpt,p.thumbnail_path,p.published_at,u.username FROM posts p JOIN users u ON p.user_id=u.id WHERE p.status='published' ORDER BY p.published_at DESC LIMIT 20");
$posts = $stmt->fetchAll();
?>

<h2>Latest Posts</h2>

<div class="grid">

<?php foreach($posts as $p): ?>

  <article class="card">
    <?php if($p['thumbnail_path']): ?>
        <img src="<?=e($p['thumbnail_path'])?>" class="card-thumb">
        <?php endif; ?>
    <h3><a href="/blogApp/php/view_post.php?id=<?=e($p['id'])?>"><?=e($p['title'])?></a></h3>

    <p class="meta">By <?=e($p['username'])?> • <?=e($p['published_at'])?></p>

    <p><?=e($p['excerpt'])?></p>

    <a href="/blogApp/php/view_post.php?id=<?=e($p['id'])?>" class="read-more">Read more</a>

  </article>


<?php endforeach; ?>
</div>

<!-- <?php include __DIR__ . '/includes/footer.php'; ?> -->
<?php require_once __DIR__.'/contact.php'; ?>
