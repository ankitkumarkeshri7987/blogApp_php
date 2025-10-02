<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/db.php';

$user = current_user();
if (!$user) 
    {
    header('Location: /blog-platform/php/login.php');
    exit;
}

$post_id = $_GET['id'] ?? null;
if (!$post_id) 
    {
    flash('error', 'Missing post ID');
    header('Location: /blogApp/php/dashboard.php');
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");

$stmt->execute([$post_id]);

$post = $stmt->fetch();

if (!$post) {
    flash('error', 'Post not found');
    header('Location: /blogApp/php/dashboard.php');
    exit;
}


if ($post['user_id'] != $user['id']) {
    flash('error', 'You do not have permission to edit this post');
    header('Location: /blogApp/php/dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
    
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        flash('error', 'Invalid CSRF token');
        header("Location: /blogApp/php/edit_post.php?id={$post_id}");
        exit;
    }
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $status = in_array($_POST['status'] ?? '', ['draft','published']) ? $_POST['status'] : 'draft';

    if (!$title || !$content) 
        {
        flash('error', 'Title and content are required');
        header("Location: /blogApp/php/edit_post.php?id={$post_id}");
        exit;
    }


    $thumbPath = $post['thumbnail_path'];

    if (!empty($_FILES['thumbnail']['name']))
         {
        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);

        $allowed = ['png','jpg','jpeg','gif'];

        if (!in_array(strtolower($ext), $allowed)) {
            flash('error', 'Invalid image type');
            header("Location: /blogApp/php/edit_post.php?id={$post_id}");
            exit;
        }
        $dest = __DIR__ . '/../assets/images/' . time() . '_' . basename($_FILES['thumbnail']['name']);
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dest)) 
        {
            $thumbPath = '/blogApp/assets/images/' . basename($dest);
        }
    }

    $excerpt = substr(strip_tags($content), 0, 200);

    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;

    $upd = $pdo->prepare("UPDATE posts SET title=?, content=?, excerpt=?, thumbnail_path=?, status=?, published_at=?, updated_at=NOW() WHERE id=?");

    $upd->execute([$title, $content, $excerpt, $thumbPath, $status, $published_at, $post_id]);

    flash('success', 'Post updated');
    header("Location: /blogApp/php/dashboard.php");
    exit;
}


include __DIR__ . '/../includes/header.php';
?>
<div class="container">
  <h2>Edit Post</h2>
  <form method="post" action="/blogApp/php/edit_post.php?id=<?= e($post['id']); ?>" enctype="multipart/form-data">

    <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">

    <label>Title</label>

    <input name="title" required value="<?= e($post['title']); ?>">

    <label>Content</label>

    <textarea name="content" rows="8" required><?= e($post['content']); ?></textarea>

    <label>Thumbnail (leave blank to keep existing)</label>

    <input type="file" name="thumbnail" accept="image/*">

    <label>Status</label>

    <select name="status">

      <option value="draft"<?= $post['status']=='draft'?' selected':''; ?>>Draft</option>

      <option value="published"<?= $post['status']=='published'?' selected':''; ?>>Published</option>

    </select>

    <button type="submit">Update</button>

  </form>

</div>
<?php

include __DIR__ . '/../includes/footer.php';
?>
