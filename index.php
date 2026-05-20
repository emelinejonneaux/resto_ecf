<?php require_once "includes/header.php"; ?>
<?php require_once "backend/db.php"; ?>

<section class="hero">
    <img src="/images/hero.png" alt="Bannière">
</section>

<section class="histoire">

    <div class="histoire-text">
        <h2>Notre histoire</h2>
        <h3>25 ans de partage</h3>

        <p>Installée à Bordeaux depuis plus de 25 ans, Vite & Gourmand est née de la passion de Julie et José pour la cuisine faite maison.</p>

        <p>Leur ambition : offrir une gastronomie généreuse, soignée et profondément humaine.</p>

        <p>Ce qui a commencé comme une aventure familiale est devenu une référence locale.</p>
    </div>

    <div class="histoire-img">
        <img src="/images/histoire.png" alt="Notre histoire">
    </div>

</section>

<section class="cta">

    <h2>Pour toutes vos commandes</h2>

    <a href="/frontend/menus/menus.php" class="btn">
        Venez découvrir nos menus
    </a>

</section>

<section class="equipe">

    <h2>Notre équipe</h2>

    <p class="intro">
        Derrière Vite & Gourmand, Julie et José travaillent main dans la main pour proposer une cuisine faite maison.
    </p>

    <div class="team">

        <div class="member">
            <img src="/images/julie.png" alt="Julie">
            <h3>Julie</h3>
            <p>Cheffe & Co-Fondatrice</p>
        </div>

        <div class="member">
            <img src="/images/jose.png" alt="José">
            <h3>José</h3>
            <p>Chef Exécutif & Co-Fondateur</p>
        </div>

    </div>

    <p>
        Julie crée des souvenirs gourmands, José garantit une cuisine généreuse et maîtrisée.<br>
        Ensemble, ils transforment chaque événement en moment gourmand.
    </p>

</section>

<section class="avis">

    <h2>Avis clients</h2>

    <div class="avis-container">

        <?php
        $avis = $pdo->query("
            SELECT a.*, u.nom
            FROM avis a
            JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
            WHERE a.statut = 'validé'
        ")->fetchAll();

        foreach ($avis as $a):
        ?>

        <div class="card-avis">
            <p class="note"><?= str_repeat("★", $a["note"]) ?></p>
            <p><?= htmlspecialchars($a["description"]) ?></p>
            <p class="author">- <?= htmlspecialchars($a["nom"]) ?></p>
        </div>

        <?php endforeach; ?>

    </div>

</section>

<?php require_once "includes/footer.php"; ?>