<?php session_start(); ?>
<?php require_once "../../includes/header.php"; ?>

<div class="auth-page">

    <h1 class="auth-title">Connexion</h1>

    <?php
    if (isset($_SESSION["message"])) {
        echo "<p class='auth-error'>".$_SESSION["message"]."</p>";
        unset($_SESSION["message"]);
    }
    ?>

    <form action="../../backend/auth/login_traitement.php" method="POST" class="auth-form">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Mot de passe" required>

        <a href="forgot_password.php" class="auth-link">
            Mot de passe oublié ?
        </a>

        <button type="submit" class="btn">
            Se connecter
        </button>

        <a href="register.php" class="auth-link">
            Créer un compte
        </a>

    </form>

</div>

<?php require_once "../../includes/footer.php"; ?>