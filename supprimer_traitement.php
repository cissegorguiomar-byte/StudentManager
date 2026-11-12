<?php

require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Vérifier la méthode d'envoi
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Méthode non autorisée.");
}

// Vérifier la présence de l'identifiant
if (!isset($_POST["id"])) {
    die("Identifiant manquant.");
}

// Récupérer et vérifier l'identifiant
$id = (int) $_POST["id"];

if ($id <= 0) {
    die("Identifiant invalide.");
}

// Préparer la requête SQL
$sql = "DELETE FROM utilisateurs WHERE id = ?";

$stmt = mysqli_prepare($connexion, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

// Exécuter la suppression
if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: selection.php");
        exit;
    } else {
        echo "Aucun étudiant trouvé avec cet identifiant.";
    }

} else {
    echo "Erreur lors de la suppression : "
         . htmlspecialchars(mysqli_stmt_error($stmt));
}

// Fermer les ressources
mysqli_stmt_close($stmt);
mysqli_close($connexion);

?>