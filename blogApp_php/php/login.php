<?php
require_once __DIR__.'/db.php';
require_once __DIR__ . '/../includes/helpers.php';


if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf'] ?? '';

    if(!verify_csrf($csrf))
        {
             flash('error','Invalid CSRF');
              header('Location: /blogApp/php/login.php'); 
              exit; }

    if(!$email || !$password)
        { 
            flash('error','All fields required');
             header('Location: /blogApp/php/login.php'); 
             exit; }

    $stmt = $pdo->prepare("SELECT id,password_hash FROM users WHERE email = ?");

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password_hash']))
        {
        $_SESSION['user_id'] = $user['id'];
        flash('success','Welcome back!');
        header('Location: /blogApp/php/dashboard.php');
         exit;
    }
     else 
        {
        flash('error','Invalid credentials');
        header('Location: /blogApp/php/login.php');
         exit;
    }
}

include __DIR__.'/../includes/header.php'; ?>
<h2>Login</h2>
<form method="post" action="/blogApp/php/login.php">

  <input type="hidden" name="csrf" value="<?=csrf_token()?>">

  <label>Email</label><input name="email" type="email" required>

  <label>Password</label><input name="password" type="password" required>

  <button type="submit">Login</button>


</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
