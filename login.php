<?php
session_start();
require_once __DIR__ . "/connexion.php";

// Si l'administrateur est déjà connecté
if (isset($_SESSION["admin_id"])) {
    header("Location: tableau_bord.php");
    exit;
}

$message = "";

// Vérifier l'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom_utilisateur"] ?? "");
    $motDePasse = $_POST["mot_de_passe"] ?? "";

    // Recherche de l'administrateur
    $sql = "SELECT id, nom_utilisateur, mot_de_passe
            FROM administrateurs
            WHERE nom_utilisateur = ?";

    $stmt = mysqli_prepare($connexion, $sql);

    mysqli_stmt_bind_param($stmt, "s", $nom);
    mysqli_stmt_execute($stmt);

    $resultat = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($resultat);

    // Vérification du mot de passe
    if ($admin && password_verify($motDePasse, $admin["mot_de_passe"])) {

        session_regenerate_id(true);

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_nom"] = $admin["nom_utilisateur"];

        header("Location: tableau_bord.php");
        exit;

    } else {
        $message = "Nom d'utilisateur ou mot de passe incorrect.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - StudentManager</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Connexion administrateur</h1>

    <?php if ($message !== ""): ?>
        <p style="color:red;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <label>Nom d'utilisateur :</label>
        <input type="text" name="nom_utilisateur" required>

        <label>Mot de passe :</label>
        <input type="password" name="mot_de_passe" required>

        <button type="submit">Se connecter</button>

    </form>

</body>
</html>