<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$user = current_user();

if(!$user)
    {
    flash('error','Login to create');
    header('Location: /blogApp/php/login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD']==='POST')
    {
    if(!verify_csrf($_POST['csrf'] ?? ''))
        { 
            flash('error','Invalid CSRF'); 
            header('Location: /blogApp/php/create_post.php'); 
            exit; }

    $title = trim($_POST['title'] ?? '');

    $content = $_POST['content'] ?? '';

    $status = in_array($_POST['status'] ?? '', ['draft','published']) ? $_POST['status'] : 'draft';

    if(!$title || !$content)
        { 
            flash('error','Title & content required'); 
            header('Location: /blogApp/php/create_post.php'); 
            exit; }

    $slug = slugify($title) . '-' . substr(bin2hex(random_bytes(4)),0,6);

    
    $thumbPath = null;

    if(!empty($_FILES['thumbnail']['name']))
        {
        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);

        $allowed = ['png','jpg','jpeg','gif'];

        if(!in_array(strtolower($ext), $allowed))
            {
                 flash('error','Invalid image type');
                  header('Location: /blogApp/php/create_post.php'); 
                  exit;
                 }
        $dest = __DIR__ . '/../assets/images/' . time() . '_' . basename($_FILES['thumbnail']['name']);

        if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dest))
            {
            $thumbPath = '/blogApp/assets/images/' . basename($dest);
        }
    }

    $published_at = $status === 'published' ? date('Y-m-d H:i:s') : null;

    $stmt = $pdo->prepare("INSERT INTO posts (user_id,title,slug,content,excerpt,thumbnail_path,status,published_at) VALUES (?,?,?,?,?,?,?,?)");

    $excerpt = substr(strip_tags($content),0,200);

    $stmt->execute([$user['id'],$title,$slug,$content,$excerpt,$thumbPath,$status,$published_at]);

    flash('success','Post saved');

    header('Location: /blogApp/php/dashboard.php'); 
    
    exit;
}

include __DIR__.'/../includes/header.php'; ?>

<h2>Create Post</h2>

<form method="post" action="/blogApp/php/create_post.php" enctype="multipart/form-data">

  <input type="hidden" name="csrf" value="<?=csrf_token()?>">

  <label>Title</label><input name="title" required>

  <label>Content</label><textarea name="content" rows="8" required></textarea>

  <label>Thumbnail</label><input type="file" name="thumbnail" accept="image/*">

  <label>Status</label>

  <select name="status"><option value="draft">Draft</option><option value="published">Publish</option></select>

  <button type="submit">Save</button>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
