<?php
require_once __DIR__ . "/protection.php";
require_once __DIR__ . "/connexion.php";

// Nombre total d'étudiants
$sql1 = "SELECT COUNT(*) AS total FROM utilisateurs";
$resultat1 = mysqli_query($connexion, $sql1);
$total = mysqli_fetch_assoc($resultat1)['total'];

// Nombre de filières
$sql2 = "SELECT COUNT(DISTINCT filiere) AS total_filiere
         FROM utilisateurs";
$resultat2 = mysqli_query($connexion, $sql2);
$totalFiliere = mysqli_fetch_assoc($resultat2)['total_filiere'];

// Moyenne générale
$sql3 = "SELECT AVG(moyenne) AS moyenne_generale
         FROM utilisateurs";
$resultat3 = mysqli_query($connexion, $sql3);
$moyenne = mysqli_fetch_assoc($resultat3)['moyenne_generale'];

$moyenne = $moyenne !== null
    ? number_format((float)$moyenne, 2, ',', ' ')
    : '0,00';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StudentManager - Tableau de bord</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- EN-TÊTE -->
    <header class="header">

        <div class="logo">
            <div class="logo-icon">🎓</div>

            <div>
                <h2>StudentManager</h2>
                <span>Gestion universitaire</span>
            </div>
        </div>

        <div class="header-label">
            ESPACE ADMINISTRATION
        </div>

    </header>
	
	<a href="logout.php" class="action-link">Déconnexion</a>

    <main class="container">

        <!-- INTRODUCTION -->
        <section class="welcome">

            <div>
                <span class="welcome-label">VOTRE ESPACE DE GESTION</span>

                <h1>Tableau de bord</h1>

                <p class="description">
                    Bienvenue dans votre système de gestion des étudiants.
                </p>
				
				<p class="description">
    Administrateur connecté :
    <?= htmlspecialchars($_SESSION["admin_nom"]) ?>
</p>
            </div>

            <div class="welcome-icon">📚</div>

        </section>

        <!-- STATISTIQUES -->
        <section class="stats">

            <div class="stat-card students">

                <div class="stat-top">
                    <span>Étudiants</span>
                    <span class="stat-icon">👥</span>
                </div>

                <p><?= $total ?></p>

                <small>Nombre total d'étudiants</small>

            </div>

            <div class="stat-card filieres">

                <div class="stat-top">
                    <span>Filières</span>
                    <span class="stat-icon">📚</span>
                </div>

                <p><?= $totalFiliere ?></p>

                <small>Filières enregistrées</small>

            </div>

            <div class="stat-card moyenne">

                <div class="stat-top">
                    <span>Moyenne générale</span>
                    <span class="stat-icon">📊</span>
                </div>

                <p><?= $moyenne ?><span class="sur-20">/20</span></p>

                <small>Moyenne de tous les étudiants</small>

            </div>

        </section>

        <!-- GESTION DES ÉTUDIANTS -->
        <section class="gestion">

            <div class="section-title">
                <div>
                    <h2>Gestion des étudiants</h2>
                    <p>Accédez rapidement aux fonctionnalités.</p>
                </div>
            </div>

            <div class="actions">

                <a href="Ajouter.php" class="action-card">

                    <div class="action-icon add-icon">＋</div>

                    <div class="action-content">
                        <h3>Ajouter un étudiant</h3>

                        <p>
                            Enregistrer un nouvel étudiant
                            dans le système.
                        </p>

                        <span class="action-link">
                            Ajouter maintenant →
                        </span>
                    </div>

                </a>

                <a href="selection.php" class="action-card">

                    <div class="action-icon list-icon">☷</div>

                    <div class="action-content">
                        <h3>Voir les étudiants</h3>

                        <p>
                            Consulter, rechercher, modifier
                            et supprimer les étudiants.
                        </p>

                        <span class="action-link">
                            Consulter la liste →
                        </span>
                    </div>

                </a>

            </div>

        </section>

    </main>

    <!-- PIED DE PAGE -->
    <footer>
        <p>
            StudentManager © 2026
            <span>|</span>
            Système de gestion des étudiants
        </p>
    </footer>

</body>
</html>