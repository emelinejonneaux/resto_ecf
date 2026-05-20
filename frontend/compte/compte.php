<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$role = $_SESSION["role"];
$prenom = $_SESSION["prenom"];

require_once "../../includes/header.php";
?>

<section class="compte-page">

    <h1 class="compte-title">Mon compte</h1>
    <h2 class="compte-subtitle">Bienvenue <?= htmlspecialchars($prenom); ?></h2>

    <div class="compte-card">

        <?php if ($role == 3): ?>

            <?php include "../includes/client.php"; ?>

        <?php elseif ($role == 2): ?>

            <?php include "../includes/employe.php"; ?>

        <?php elseif ($role == 1): ?>

            <?php include "../includes/admin.php"; ?>

        <?php else: ?>

            <p>Rôle inconnu</p>

        <?php endif; ?>

    </div>

    <div class="compte-actions">
        <a href="../auth/logout.php" class="btn">Déconnexion</a>
    </div>

</section>

<?php require_once "../../includes/footer.php"; ?>