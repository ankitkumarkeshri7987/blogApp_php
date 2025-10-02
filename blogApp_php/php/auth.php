<?php

require_once __DIR__ . '/../includes/helpers.php';

require_once __DIR__ . '/db.php';

function require_login() 
{
    $user = current_user();
    if (!$user) {
        header('Location: /blogApp/php/login.php');
        exit;
    }
    return $user;
}

function is_admin() 
{
    $user = current_user();
    
    return $user && $user['role'] === 'admin';
}
