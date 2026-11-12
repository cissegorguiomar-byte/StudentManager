<?php

require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Vérifier la méthode d'envoi
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Méthode non autorisée.");
}

// Récupération des données
$matricule = trim($_POST["matricule"] ?? "");
$nom = trim($_POST["nom"] ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$email = trim($_POST["email"] ?? "");
$telephone = trim($_POST["telephone"] ?? "");

$date_naissance = !empty($_POST["date_naissance"])
    ? $_POST["date_naissance"] : null;

$adresse = trim($_POST["adresse"] ?? "");
$filiere = trim($_POST["filiere"] ?? "");
$niveau = trim($_POST["niveau"] ?? "");
$moyenne = $_POST["moyenne"] ?? "";
$motDePasse = $_POST["passe"] ?? "";

// Vérification des champs obligatoires
if (
    $matricule === "" ||
    $nom === "" ||
    $prenom === "" ||
    $email === "" ||
    $filiere === "" ||
    $niveau === "" ||
    $motDePasse === "" ||
    $moyenne === ""
) {
    die("Veuillez remplir tous les champs obligatoires.");
}

// Vérifier l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Adresse email invalide.");
}

// Vérifier la moyenne
if (!is_numeric($moyenne) || $moyenne < 0 || $moyenne > 20) {
    die("La moyenne doit être comprise entre 0 et 20.");
}

$moyenne = (float) $moyenne;

// Hacher le mot de passe
$passe = password_hash($motDePasse, PASSWORD_DEFAULT);

// Préparer la requête SQL
$sql = "INSERT INTO utilisateurs
(matricule, nom, prenom, email, telephone,
date_naissance, adresse, filiere, niveau, moyenne, passe)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connexion, $sql);

// Associer les valeurs aux paramètres
mysqli_stmt_bind_param(
    $stmt,
    "sssssssssds",
    $matricule,
    $nom,
    $prenom,
    $email,
    $telephone,
    $date_naissance,
    $adresse,
    $filiere,
    $niveau,
    $moyenne,
    $passe
);

// Exécuter l'insertion
if (mysqli_stmt_execute($stmt)) {

    header("Location: selection.php");
    exit;

} else {

    if (mysqli_stmt_errno($stmt) == 1062) {
        echo "Erreur : ce matricule ou cette adresse email existe déjà.";
    } else {
        echo "Erreur lors de l'ajout : "
             . htmlspecialchars(mysqli_stmt_error($stmt));
    }
}

// Fermer les ressources
mysqli_stmt_close($stmt);
mysqli_close($connexion);

?>