<?php

require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Récupérer les filtres
$recherche = trim($_GET["q"] ?? "");
$filiere = trim($_GET["filiere"] ?? "");
$niveau = trim($_GET["niveau"] ?? "");

$motif = "%" . $recherche . "%";

// Préparer la requête
$sql = "SELECT matricule, nom, prenom, email,
        filiere, niveau, moyenne
        FROM utilisateurs
        WHERE
        (matricule LIKE ?
        OR nom LIKE ?
        OR prenom LIKE ?
        OR filiere LIKE ?
        OR niveau LIKE ?)
        AND (? = '' OR filiere = ?)
        AND (? = '' OR niveau = ?)
        ORDER BY nom ASC";

$stmt = mysqli_prepare($connexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssss",
    $motif,
    $motif,
    $motif,
    $motif,
    $motif,
    $filiere,
    $filiere,
    $niveau,
    $niveau
);

// Exécuter la requête
mysqli_stmt_execute($stmt);

$resultat = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Liste des étudiants - StudentManager</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        h1, p {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #999;
            padding: 9px;
            text-align: left;
        }

        th {
            background: #173b70;
            color: white;
        }

        .bouton {
            background: #173b70;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }

        @media print {
            .bouton {
                display: none;
            }

            body {
                margin: 10px;
            }
        }
    </style>
</head>

<body>

    <button class="bouton" onclick="window.print()">
        Enregistrer en PDF / Imprimer
    </button>

    <h1>StudentManager</h1>

    <p>Liste des étudiants</p>

    <table>
        <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Filière</th>
            <th>Niveau</th>
            <th>Moyenne</th>
        </tr>

        <?php if (mysqli_num_rows($resultat) > 0) { ?>

            <?php while ($etudiant = mysqli_fetch_assoc($resultat)) { ?>

                <tr>
                    <td><?= htmlspecialchars($etudiant["matricule"]) ?></td>
                    <td><?= htmlspecialchars($etudiant["nom"]) ?></td>
                    <td><?= htmlspecialchars($etudiant["prenom"]) ?></td>
                    <td><?= htmlspecialchars($etudiant["email"]) ?></td>
                    <td><?= htmlspecialchars($etudiant["filiere"]) ?></td>
                    <td><?= htmlspecialchars($etudiant["niveau"]) ?></td>
                    <td><?= htmlspecialchars((string) $etudiant["moyenne"]) ?>/20</td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="7" style="text-align:center;">
                    Aucun étudiant trouvé.
                </td>
            </tr>

        <?php } ?>

    </table>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($connexion);
?>