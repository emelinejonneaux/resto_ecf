<?php session_start(); ?>
<?php require_once "../../includes/header.php"; ?>

<div class="auth-page">

    <h1 class="auth-title">Contact</h1>

    <?php if (isset($_SESSION["message"])): ?>
        <p class="auth-error" style="color:green;">
            <?= $_SESSION["message"]; unset($_SESSION["message"]); ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <p class="auth-error">
            <?= $_SESSION["error"]; unset($_SESSION["error"]); ?>
        </p>
    <?php endif; ?>

    <form class="auth-form" method="POST" action="contact_send.php">

        <input type="text" name="titre" placeholder="Titre" required>

        <textarea name="description" placeholder="Votre message..." required></textarea>

        <input type="email" name="email" placeholder="Votre email" required>

        <button type="submit" class="btn">
            Envoyer
        </button>

    </form>

</div>

<?php require_once "../../includes/footer.php"; ?>