<?php
require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Récupérer et vérifier l'identifiant
$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    die("Identifiant invalide.");
}

// Rechercher l'étudiant
$sql = "SELECT id, matricule, nom, prenom, email,
        telephone, date_naissance, adresse,
        filiere, niveau, moyenne
        FROM utilisateurs
        WHERE id = ?";

$stmt = mysqli_prepare($connexion, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultat = mysqli_stmt_get_result($stmt);
$etudiant = mysqli_fetch_assoc($resultat);

mysqli_stmt_close($stmt);

if (!$etudiant) {
    die("Étudiant introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Modifier un étudiant</h1>

<a href="selection.php">Retour à la liste des étudiants</a>

<form action="modifier_traitement.php" method="POST">

    <input type="hidden" name="id"
           value="<?= (int) $etudiant['id'] ?>">

    <label>Matricule :</label>
    <input type="text" name="matricule"
           value="<?= htmlspecialchars($etudiant['matricule']) ?>" required>

    <label>Nom :</label>
    <input type="text" name="nom"
           value="<?= htmlspecialchars($etudiant['nom']) ?>" required>

    <label>Prénom :</label>
    <input type="text" name="prenom"
           value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>

    <label>Email :</label>
    <input type="email" name="email"
           value="<?= htmlspecialchars($etudiant['email']) ?>" required>

    <label>Téléphone :</label>
    <input type="text" name="telephone"
           value="<?= htmlspecialchars($etudiant['telephone'] ?? '') ?>">

    <label>Date de naissance :</label>
    <input type="date" name="date_naissance"
           value="<?= htmlspecialchars($etudiant['date_naissance'] ?? '') ?>">

    <label>Adresse :</label>
    <input type="text" name="adresse"
           value="<?= htmlspecialchars($etudiant['adresse'] ?? '') ?>">

    <label>Filière :</label>
    <input type="text" name="filiere"
           value="<?= htmlspecialchars($etudiant['filiere']) ?>" required>

    <label>Niveau :</label>
    <input type="text" name="niveau"
           value="<?= htmlspecialchars($etudiant['niveau']) ?>" required>

    <label>Moyenne :</label>
    <input type="number" name="moyenne"
           min="0" max="20" step="0.01"
           value="<?= htmlspecialchars((string) $etudiant['moyenne']) ?>"
           required>

    <button type="submit">Enregistrer les modifications</button>

</form>

</body>
</html>