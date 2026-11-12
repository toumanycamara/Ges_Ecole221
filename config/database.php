<?php
// ===== EN LOCAL (XAMPP) - je laiise ces lignes pour travailler sur mon PC =====
$host = 'localhost';
$db   = 'ecole221';
$user = 'root';
$pass = '';

// ===== EN LIGNE (InfinityFree) =====
// $host    = 'sql302.infinityfree.com';
// $db      = 'if0_43251556_ecole221';
// $user    = 'if0_43251556';
// $pass    = 'eLEkPRU8J6raoHR';

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
} catch (PDOException $e) {
  die('Erreur de connexion a la base : ' . $e->getMessage());
}