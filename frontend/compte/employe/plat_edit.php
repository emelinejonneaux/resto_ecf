<?php
session_start();
require_once "../../../backend/db.php";
require_once "../../../includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}

if ($_SESSION["role"] == 3) {
    die("Accès refusé");
}

$id = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM plat WHERE plat_id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    header("Location: ../compte.php");
    exit;
}
?>

<div class="auth-page">

    <h1 class="auth-title">Modifier plat</h1>

    <form action="plat_update.php" method="POST" enctype="multipart/form-data" class="auth-form">

        <input type="hidden" name="id" value="<?= (int)$p["plat_id"] ?>">

        <label for="titre">Titre du plat</label>
        <input type="text" id="titre" name="titre_plat"
               value="<?= htmlspecialchars($p["titre_plat"]) ?>" required>

        <button type="submit" class="btn">Enregistrer</button>

    </form>

    <a href="../compte.php" class="auth-link">⬅ Retour</a>

</div>

<?php require_once "../../../includes/footer.php"; ?>