<?php
session_start();
require_once "../../../backend/db.php";
require_once "../../../includes/header.php";

/* 🔐 sécurité */
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}

if ($_SESSION["role"] == 3) {
    die("Accès refusé");
}

$id = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM horaire WHERE horaire_id = ?");
$stmt->execute([$id]);
$h = $stmt->fetch();

if (!$h) {
    header("Location: ../compte.php");
    exit;
}
?>

<div class="auth-page">

    <h1 class="auth-title">Modifier horaire</h1>

    <form action="horaire_update.php" method="POST" class="auth-form">

        <input type="hidden" name="id" value="<?= (int)$h["horaire_id"] ?>">

        <label for="jour">Jour</label>
        <input type="text" id="jour" name="jour" value="<?= htmlspecialchars($h["jour"]) ?>" required>

        <label for="ouverture">Heure ouverture</label>
        <input type="time" id="ouverture" name="ouverture" value="<?= htmlspecialchars($h["heure_ouverture"]) ?>" required>

        <label for="fermeture">Heure fermeture</label>
        <input type="time" id="fermeture" name="fermeture" value="<?= htmlspecialchars($h["heure_fermeture"]) ?>" required>

        <button type="submit" class="btn">Enregistrer</button>

    </form>

    <a href="../compte.php" class="auth-link">⬅ Retour</a>

</div>

<?php require_once "../../../includes/footer.php"; ?>