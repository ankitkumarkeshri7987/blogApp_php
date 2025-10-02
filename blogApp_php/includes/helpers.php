<?php
function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function slugify($text)
{

    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    $text = iconv('utf-8','us-ascii//TRANSLIT', $text);

    $text = preg_replace('~[^-\w]+~', '', $text);

    $text = trim($text, '-');

    $text = preg_replace('~-+~', '-', $text);

    $text = strtolower($text);

    return $text ?: 'n-a';
}

function flash($key = null, $msg = null){

    if($key && $msg)
        {

        $_SESSION['flash'][$key] = $msg;
    } 
    elseif($key)
        {
        if(isset($_SESSION['flash'][$key])) {
            $m = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $m;
        }
        return null;
    }
}

function csrf_token()
{
    if(empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

    return $_SESSION['csrf'];
}

function verify_csrf($token)
{
    return hash_equals($_SESSION['csrf'] ?? '', $token ?? '');
}
