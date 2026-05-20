<?php
session_start();
require_once "../../../includes/header.php";
?>

<div class="auth-page">

    <h1 class="auth-title">Créer un employé</h1>

    <?php if (!empty($_SESSION["error"])): ?>
        <p class="auth-error">
            <?= htmlspecialchars($_SESSION["error"]) ?>
        </p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION["success"])): ?>
        <p class="auth-success">
            <?= htmlspecialchars($_SESSION["success"]) ?>
        </p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <form action="employe_store.php" method="POST" class="auth-form">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Email" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe" required>

        <button type="submit" class="btn">
            Créer employé
        </button>

    </form>

    <a href="../compte.php" class="auth-link">⬅ Retour</a>

</div>

<?php require_once "../../../includes/footer.php"; ?>