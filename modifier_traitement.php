<?php

require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Vérifier que le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Méthode non autorisée.");
}

// Récupération des données
$id = (int) ($_POST["id"] ?? 0);
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
$moyenne = (float) ($_POST["moyenne"] ?? -1);

// Vérification des champs obligatoires
if (
    $id <= 0 ||
    $matricule === "" ||
    $nom === "" ||
    $prenom === "" ||
    $email === "" ||
    $filiere === "" ||
    $niveau === ""
) {
    die("Veuillez remplir tous les champs obligatoires.");
}

// Vérifier le format de l'adresse email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Adresse email invalide.");
}

// Vérifier la moyenne
if ($moyenne < 0 || $moyenne > 20) {
    die("La moyenne doit être comprise entre 0 et 20.");
}

// Préparer la requête SQL
$sql = "UPDATE utilisateurs SET
matricule = ?, nom = ?, prenom = ?, email = ?,
telephone = ?, date_naissance = ?, adresse = ?,
filiere = ?, niveau = ?, moyenne = ?
WHERE id = ?";

$stmt = mysqli_prepare($connexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssdi",
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
    $id
);

// Exécuter la modification
if (mysqli_stmt_execute($stmt)) {

    header("Location: selection.php");
    exit;

} else {

    if (mysqli_stmt_errno($stmt) == 1062) {
        echo "Erreur : ce matricule ou cette adresse email existe déjà.";
    } else {
        echo "Erreur lors de la modification : "
             . htmlspecialchars(mysqli_stmt_error($stmt));
    }
}

// Fermer la requête et la connexion
mysqli_stmt_close($stmt);
mysqli_close($connexion);

?>