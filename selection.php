<?php
require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Récupérer les valeurs envoyées par le formulaire
$recherche = trim($_GET["q"] ?? "");
$filiere = trim($_GET["filiere"] ?? "");
$niveau = trim($_GET["niveau"] ?? "");

// Préparer la recherche
$motif = "%" . $recherche . "%";

// Récupérer les filières disponibles
$filieres = mysqli_query(
    $connexion,
    "SELECT DISTINCT filiere FROM utilisateurs
     WHERE filiere <> ''
     ORDER BY filiere ASC"
);

// Récupérer les niveaux disponibles
$niveaux = mysqli_query(
    $connexion,
    "SELECT DISTINCT niveau FROM utilisateurs
     WHERE niveau <> ''
     ORDER BY niveau ASC"
);

// Requête avec recherche et filtres
$sql = "SELECT id, matricule, nom, prenom, email,
        telephone, filiere, niveau, moyenne
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

mysqli_stmt_execute($stmt);

$resultat = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Liste des étudiants</h1>

<a href="tableau_bord.php">Retour au tableau de bord </a>

<form action="selection.php" method="GET" class="recherche">

    <!-- Recherche par nom ou matricule -->
    <input
        type="search"
        name="q"
        placeholder="Nom, prénom ou matricule..."
        value="<?= htmlspecialchars($recherche) ?>"
    >

    <!-- Filtre par filière -->
    <select name="filiere">
        <option value="">Toutes les filières</option>

        <?php while ($f = mysqli_fetch_assoc($filieres)) { ?>
            <option
                value="<?= htmlspecialchars($f['filiere']) ?>"
                <?= ($filiere === $f['filiere']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($f['filiere']) ?>
            </option>
        <?php } ?>
    </select>

    <!-- Filtre par niveau -->
    <select name="niveau">
        <option value="">Tous les niveaux</option>

        <?php while ($n = mysqli_fetch_assoc($niveaux)) { ?>
            <option
                value="<?= htmlspecialchars($n['niveau']) ?>"
                <?= ($niveau === $n['niveau']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($n['niveau']) ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Filtrer</button>

    <a href="selection.php">Réinitialiser</a>

</form>

<a href="imprimer.php?q=<?= urlencode($recherche) ?>&filiere=<?= urlencode($filiere) ?>&niveau=<?= urlencode($niveau) ?>"
   target="_blank"
   class="btn-pdf">
   Exporter en PDF
</a>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Matricule</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Téléphone</th>
    <th>Filière</th>
    <th>Niveau</th>
    <th>Moyenne</th>
	<th>Actions</th>
</tr>

<?php if (mysqli_num_rows($resultat) > 0) { ?>

    <?php while ($etudiant = mysqli_fetch_assoc($resultat)) { ?>
	
<tr>
    <td><?= htmlspecialchars($etudiant['id']) ?></td>
    <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
    <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
    <td><?= htmlspecialchars($etudiant['email']) ?></td>
    <td><?= htmlspecialchars($etudiant['telephone'] ?? '') ?></td>
    <td><?= htmlspecialchars($etudiant['filiere']) ?></td>
    <td><?= htmlspecialchars($etudiant['niveau']) ?></td>
    <td><?= htmlspecialchars($etudiant['moyenne']) ?></td>
	<td>
	    <a href="modifier.php?id=<?= $etudiant['id'] ?>">
		    Modifier
		</a>
		<form action="supprimer_traitement.php" method="POST"
      onsubmit="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">

    <input type="hidden" name="id"
           value="<?= $etudiant['id'] ?>">

    <button type="submit">Supprimer</button>

</form>
	</td>
</tr>
<?php } ?>

<?php } else { ?>

<tr>
    <td colspan="10">Aucun étudiant trouvé.</td>
</tr>

<?php } ?>

</table>

</body>
</html>