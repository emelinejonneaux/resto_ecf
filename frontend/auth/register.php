<?php session_start(); ?>
<?php require_once "../../includes/header.php"; ?>

<div class="auth-page">

    <h1 class="auth-title">Créer un compte</h1>

    <?php
    if (isset($_SESSION["message"])) {
        echo "<p class='auth-error'>".$_SESSION["message"]."</p>";
        unset($_SESSION["message"]);
    }
    ?>

    <form action="../../backend/auth/register_traitement.php" method="POST" class="auth-form">

        <input type="text" name="nom" placeholder="Nom" required>

        <input type="text" name="prenom" placeholder="Prénom" required>

        <input type="text" name="telephone" placeholder="Numéro de GSM" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="text" name="adresse" placeholder="Adresse postale" required>

        <input type="text" name="ville" placeholder="Ville" required>

        <input type="text" name="pays" placeholder="Pays" required>

        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit" class="btn">
            S'inscrire
        </button>

    </form>

    <a href="login.php" class="auth-link">
        Déjà un compte ? Se connecter
    </a>

</div>

<?php require_once "../../includes/footer.php"; ?>