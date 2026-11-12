<?php
require_once __DIR__ . "/protection.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
    <link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <h1>Ajouter un étudiant</h1>

    <form action="ajouter_traitement.php" method="POST">

        <label>Matricule :</label>
        <input type="text" name="matricule" required>

        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone">

        <label>Date de naissance :</label>
        <input type="date" name="date_naissance">

        <label>Adresse :</label>
        <input type="text" name="adresse">

        <label>Filière :</label>
        <input type="text" name="filiere" required>

        <label>Niveau :</label>
        <select name="niveau" required>
            <option value="">Choisir le niveau</option>
            <option value="L1">Licence 1</option>
            <option value="L2">Licence 2</option>
            <option value="L3">Licence 3</option>
            <option value="M1">Master 1</option>
            <option value="M2">Master 2</option>
        </select>

        <label>Moyenne :</label>
        <input type="number" name="moyenne"
               min="0" max="20" step="0.01" value="0" required>

        <label>Mot de passe :</label>
        <input type="password" name="passe" required>

        <button type="submit">Ajouter l'étudiant</button>

    </form>

</body>
</html>