<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

<header class="header">
    <div class="logo">
        <h2>Vite & Gourmand</h2>
    </div>
     <div class="burger" onclick="toggleMenu()">☰</div>
    <nav class="nav">
        <a href="/index.php">Accueil</a>
        <a href="/frontend/menus/menus.php">Menus</a>
        <a href="/frontend/contact/contact.php">Contact</a>

        <?php if (!empty($_SESSION["user_id"])): ?>
            <a href="/frontend/compte/compte.php">Mon compte</a>
            <a href="/frontend/auth/logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="/frontend/auth/login.php">Connexion</a>
        <?php endif; ?>
    </nav>

</header>

<script>
function toggleMenu() {
    document.querySelector('.nav').classList.toggle('active');
}
</script>