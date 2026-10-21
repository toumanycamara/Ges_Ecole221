<?php
$hote = "localhost";
$base = "ecole221";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$hote;dbname=$base;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
die("Erreur de connexion : " . $e->getMessage());
    }

?>
