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

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    header("Location: ../compte.php");
    exit;
}
?>

<div class="auth-page">

    <h1 class="auth-title">Modifier menu</h1>

    <form action="menu_update.php" method="POST" class="auth-form">

        <input type="hidden" name="id" value="<?= (int)$m["menu_id"] ?>">

        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre"
               value="<?= htmlspecialchars($m["titre"]) ?>" required>

        <label for="description">Description</label>
        <input type="text" id="description" name="description"
               value="<?= htmlspecialchars($m["description"]) ?>">

        <label for="nb">Nombre minimum de personnes</label>
        <input type="number" id="nb" name="nombre_personne_minimum"
               value="<?= (int)$m["nombre_personne_minimum"] ?>">

        <label for="prix">Prix par personne</label>
        <input type="number" step="0.01" id="prix" name="prix_par_personne"
               value="<?= (float)$m["prix_par_personne"] ?>">

        <button type="submit" class="btn">Enregistrer</button>

    </form>

    <a href="../compte.php" class="auth-link">⬅ Retour</a>

</div>

<?php require_once "../../../includes/footer.php"; ?>