<?php
session_start();

// Supprimer les données de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers la connexion
header("Location: login.php");
exit;
?>