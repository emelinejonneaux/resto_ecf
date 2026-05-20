<?php
session_start();
require_once "../../../backend/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}

$commande_id = $_GET["id"] ?? null;
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM commande WHERE commande_id = ? AND utilisateur_id = ?");
$stmt->execute([$commande_id, $user_id]);
$cmd = $stmt->fetch();

if (!$cmd) {
    header("Location: ../compte.php");
    exit;
}

require_once "../../../includes/header.php";
?>

<div class="auth-page">

    <h1 class="auth-title">Modifier commande</h1>

    <form action="commande_update.php" method="POST" class="auth-form">

        <input type="hidden" name="commande_id" value="<?= htmlspecialchars($cmd["commande_id"]) ?>">

        <label>Date prestation</label>
        <input type="date" name="date_prestation" value="<?= htmlspecialchars($cmd["date_prestation"]) ?>" required>

        <label>Heure livraison</label>
        <input type="time" name="heure_livraison" value="<?= htmlspecialchars($cmd["heure_livraison"]) ?>" required>

        <label>Adresse</label>
        <input type="text" name="adresse_livraison" value="<?= htmlspecialchars($cmd["adresse_livraison"]) ?>" required>

        <label>Ville</label>
        <input type="text" name="ville_livraison" value="<?= htmlspecialchars($cmd["ville_livraison"]) ?>" required>

        <label>Nombre de personnes</label>
        <input type="number" name="nombre_personne" value="<?= htmlspecialchars($cmd["nombre_personne"]) ?>" required>

        <button type="submit" class="btn">
            Enregistrer
        </button>

    </form>

    <a href="../compte.php" class="auth-link">
        ⬅ Retour
    </a>

</div>

<?php require_once "../../../includes/footer.php"; ?>