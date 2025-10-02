<?php
require_once __DIR__.'/../php/db.php';
require_once __DIR__.'/helpers.php';
$user = current_user();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width,initial-scale=1">

  <title>PLATFORM FOR SHARING YOUR TECH THOUGHTS </title>

  <link rel="stylesheet" href="/blogApp/assets/css/style.css">
</head>
<body>

<header class="site-header">

  <div class="container">

    <a href="/blogApp/index.php" class="logo">PLATFORM FOR SHARING YOUR TECH THOUGHTS</a>
    <nav>
      <a href="/blogApp/index.php">Home</a>

      <a href="/blogApp/about.php">About</a>

      <?php if($user): ?>

        <a href="/blogApp/php/dashboard.php">Dashboard</a>

        <a href="/blogApp/php/logout.php">Logout (<?=e($user['username'])?>)</a>

      <?php else: ?>

        <a href="/blogApp/php/login.php">Login</a>

        <a href="/blogApp/php/register.php">Register</a>

      <?php endif; ?>

    </nav>
  </div>

</header>

<main class="container">

<?php if($msg = flash('success')): ?>

  <div class="flash success"><?=e($msg)?></div>

<?php endif; ?>

<?php if($msg = flash('error')): ?>

  <div class="flash error"><?=e($msg)?></div>
  
<?php endif; ?>
