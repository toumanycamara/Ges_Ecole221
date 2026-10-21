<?php
// Déconnexion : on ferme la session et on renvoie au login
session_start();
$_SESSION = [];
session_destroy();
header('Location: loginEcole221.php');
exit;