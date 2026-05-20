<?php session_start(); ?>
<?php require_once "../../includes/header.php"; ?>

<div class="auth-page">

    <h1 class="auth-title">Mot de passe oublié</h1>

    <?php
    if (isset($_SESSION["message"])) {
        echo "<p class='auth-error'>".$_SESSION["message"]."</p>";
        unset($_SESSION["message"]);
    }
    ?>

    <form class="auth-form" action="../../backend/auth/forgot_traitement.php" method="POST">

        <input type="email" name="email" placeholder="Votre email" required>

        <button type="submit" class="btn">
            Envoyer
        </button>

    </form>

    <a class="auth-link" href="login.php">
        ⬅ Retour à la connexion
    </a>

</div>

<?php require_once "../../includes/footer.php"; ?>