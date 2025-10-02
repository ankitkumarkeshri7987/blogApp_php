<?php

session_start();

define('DB_HOST','127.0.0.1');
define('DB_NAME','blogApp');
define('DB_USER','root');
define('DB_PASS',''); 

try{
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS,
     [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} 
catch (PDOException $e)
{
    die("DB Connection failed: " . $e->getMessage());
}

function current_user(){
    global $pdo;
    if(!empty($_SESSION['user_id']))
    {
        $stmt = $pdo->prepare("SELECT id, username, email, avatar_path, role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}
