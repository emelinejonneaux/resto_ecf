<?php
session_start();
require_once "../../includes/header.php";
require_once "../../backend/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$menu_id = (int)($_GET["menu_id"] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch();

if (!$menu) {
    header("Location: ../menus/menus.php");
    exit;
}
?>

<section class="commande-page">

    <?php if (!empty($_SESSION["message"])): ?>
        <p class="commande-message">
            <?= htmlspecialchars($_SESSION["message"]); unset($_SESSION["message"]); ?>
        </p>
    <?php endif; ?>

    <h1 class="commande-title">Commander un menu</h1>

    <div class="commande-back">
        <a href="../menus/menus.php">⬅ Retour aux menus</a>
    </div>

    <form action="commande_store.php" method="POST" class="commande-form">

        <input type="hidden" name="menu_id" value="<?= $menu_id ?>">

        <div class="commande-grid">

            <!-- COLONNE GAUCHE -->
            <div class="commande-col">

                <div class="commande-card">
                    <h2>Client</h2>

                    <div class="commande-readonly">
                        <input type="text" value="<?= htmlspecialchars($user["prenom"]) ?>" disabled>
                        <input type="text" value="<?= htmlspecialchars($user["nom"]) ?>" disabled>
                        <input type="email" value="<?= htmlspecialchars($user["email"]) ?>" disabled>
                    </div>
                </div>

                <div class="commande-card">
                    <h2>Livraison</h2>

                    <label class="commande-check">
                        <input type="checkbox" id="livraisonCheck" name="livraison">
                        <span>Livraison (+5 €)</span>
                    </label>

                    <div id="adresseBox" class="adresse-box" style="display:none;">
                        <input type="text" name="adresse_livraison" placeholder="Adresse de livraison">
                    </div>

                    <div class="commande-fields">
                        <input type="date" name="date_prestation" required>
                        <input type="time" name="heure_livraison" required>
                    </div>
                </div>

                <div class="commande-card">
                    <h2>Commentaire</h2>
                    <textarea name="commentaire" placeholder="Informations supplémentaires..."></textarea>
                </div>

            </div>

            <!-- COLONNE DROITE -->
            <div class="commande-col">

                <div class="commande-card">
                    <h2>Menu sélectionné</h2>

                    <p class="commande-menu-title">
                        <strong><?= htmlspecialchars($menu["titre"]) ?></strong>
                    </p>

                    <label for="personnes">Nombre de personnes</label>
                    <input
                        type="number"
                        id="personnes"
                        name="nombre_personne"
                        min="<?= (int)$menu["nombre_personne_minimum"] ?>"
                        value="<?= (int)$menu["nombre_personne_minimum"] ?>"
                        required
                    >

                    <p class="commande-min">
                        Minimum : <?= (int)$menu["nombre_personne_minimum"] ?> personnes
                    </p>
                </div>

                <div class="commande-card">
                    <h2>Détail prix</h2>
                    <div id="prixDetail" class="prix-detail"></div>
                </div>

                <div class="commande-submit">
                    <button type="submit" class="btn">Valider la commande</button>
                </div>

            </div>

        </div>

    </form>

</section>

<script>
let base = <?= (float)$menu["prix_par_personne"] ?>;
let min = <?= (int)$menu["nombre_personne_minimum"] ?>;
let livraisonPrix = 5;

function calculPrix() {
    let p = parseInt(document.getElementById("personnes").value) || min;
    let livraison = document.getElementById("livraisonCheck").checked;

    let prixMenu = p * base;

    let reduc = 0;
    if (p >= min + 5) {
        reduc = prixMenu * 0.10;
    }

    let prixFinalMenu = prixMenu - reduc;
    let prixLivraison = livraison ? livraisonPrix : 0;
    let total = prixFinalMenu + prixLivraison;

    document.getElementById("prixDetail").innerHTML = `
        <p> Menu : ${prixFinalMenu.toFixed(2)} €</p>
        <p>Livraison : ${prixLivraison.toFixed(2)} €</p>
        ${reduc > 0 ? `<p> Réduction : -${reduc.toFixed(2)} €</p>` : ""}
        <p class="prix-total">TOTAL : ${total.toFixed(2)} €</p>
    `;
}

document.getElementById("personnes").addEventListener("input", calculPrix);

document.getElementById("livraisonCheck").addEventListener("change", function () {
    document.getElementById("adresseBox").style.display = this.checked ? "block" : "none";
    calculPrix();
});

calculPrix();
</script>

<?php require_once "../../includes/footer.php"; ?>