<?php
session_start();
require_once "../../../backend/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}

if ($_SESSION["role"] != 3) {
    die("Accès refusé");
}

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

require_once "../../../includes/header.php";
?>

<div class="auth-page">

    <h1 class="auth-title">Modifier mes informations</h1>

    <form action="profil_update.php" method="POST" class="auth-form">

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user["nom"]) ?>" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user["prenom"]) ?>" required>

        <label for="telephone">Téléphone</label>
        <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($user["telephone"]) ?>">

        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($user["adresse"]) ?>">

        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($user["ville"]) ?>">

        <label for="pays">Pays</label>
        <input type="text" id="pays" name="pays" value="<?= htmlspecialchars($user["pays"]) ?>">

        <button type="submit" class="btn">Enregistrer</button>

    </form>

    <a href="../compte.php" class="auth-link">⬅ Retour</a>

</div>

<?php require_once "../../../includes/footer.php"; ?>