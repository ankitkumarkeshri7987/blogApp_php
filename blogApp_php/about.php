<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/php/db.php';

$user = current_user();

include __DIR__ . '/includes/header.php';
?>

<div class="content">
  <h2>About This BlogPlatform</h2>

  <p>Welcome to BlogPlatform, a  multi-user blogging platform where anyone can register and post their own thought to anyone .</p>

  <p>Here you can:</p>

  <ul>
    <li>Register and login</li>

    <li>Create, edit, publish and delete blog posts</li>

    <li>Comment on and like other people’s posts</li>

    <li>View author profiles</li>

  </ul>
</div>

<?php
include __DIR__ . '/includes/footer.php';
?>
