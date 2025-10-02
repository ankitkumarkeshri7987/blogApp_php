<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/db.php';   


if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
    $username = trim($_POST['username'] ?? '');

    $email = trim($_POST['email'] ?? '');

    $password = $_POST['password'] ?? '';

    $csrf = $_POST['csrf'] ?? '';

    if(!verify_csrf($csrf))
        {
        flash('error','Invalid CSRF token'); 
        header('Location: /blogApp/php/register.php'); 
        exit;
    }

    if(!$username || !$email || !$password)
        {
        flash('error','All fields required');

        header('Location: /blogApp/php/register.php');
         exit;
    }

    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");

    $stmt->execute([$email, $username]);

    if($stmt->fetch())
        {
        flash('error','Email or username already exists');

        header('Location: /blogApp/php/register.php'); exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash) VALUES (?,?,?)");

    $stmt->execute([$username,$email,$hash]);

    flash('success','Registration success. Login now.');

    header('Location: /blogApp/php/login.php'); exit;
}
include __DIR__ . '/../includes/header.php';
?>
<h2>Register</h2>

<form method="post" action="/blogApp/php/register.php">

  <input type="hidden" name="csrf" value="<?=csrf_token()?>">

  <label>Username</label><input name="username" required>

  <label>Email</label><input name="email" type="email" required>

  <label>Password</label><input name="password" type="password" required>

  <button type="submit">Register</button>
  
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
