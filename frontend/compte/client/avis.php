<?php require_once "../../../includes/header.php"; ?>

<div class="auth-page">

    <h1 class="auth-title">Donner un avis</h1>

    <form action="avis_save.php" method="POST" class="auth-form">

        <input type="hidden" name="commande_id" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">

        <label>Note (1 à 5)</label>
        <input type="number" name="note" min="1" max="5" required>

        <label>Commentaire</label>
        <textarea name="description" placeholder="Votre avis..." required></textarea>

        <button type="submit" class="btn">
            Envoyer
        </button>

    </form>

</div>

<?php require_once "../../../includes/footer.php"; ?>