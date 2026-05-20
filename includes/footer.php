<?php
require_once __DIR__ . "/../backend/db.php";
$horaires = $pdo->query("SELECT * FROM horaire")->fetchAll();
?>

<footer class="footer">

    <div class="footer-container">

        <div class="footer-col">
            <h4>Contact</h4>
            <p>Vite et Gourmand</p>
            <p>33000 Bordeaux</p>
            <p><strong>Tél.</strong> 02 97 66 69 47</p>
            <p>contact@vitegourmand.com</p>
        </div>
        <div class="footer-col">
            <h4>Liens</h4>
            <a href="/frontend/legal/cgv.php">Conditions générales de vente</a>
            <a href="/frontend/legal/mentions_legales.php">Mentions légales</a>
        </div>
        <div class="footer-col">
            <h4>Horaires d'ouverture</h4>

            <?php foreach ($horaires as $h): ?>
                <p>
                    <?= ucfirst($h["jour"]) ?> :
                    <?= $h["heure_ouverture"] ?> - <?= $h["heure_fermeture"] ?>
                </p>
            <?php endforeach; ?>

        </div>

    </div>

</footer>

</body>
</html>