<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'blogApp';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, 
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ Database '$dbname' exists. Connection OK.";
}
 catch (PDOException $e) 
 {
    echo "❌ Error: " . $e->getMessage();
}
