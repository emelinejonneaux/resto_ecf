<?php
session_start();
require_once "../../backend/db.php";

$where = [];
$params = [];

if (isset($_GET["prix"]) && $_GET["prix"] !== "") {
    $where[] = "m.prix_par_personne <= ?";
    $params[] = (int) $_GET["prix"];
}

if (isset($_GET["personnes"]) && $_GET["personnes"] !== "") {
    $where[] = "m.nombre_personne_minimum <= ?";
    $params[] = (int) $_GET["personnes"];
}

$joinTheme = "";
if (!empty($_GET["themes"])) {
    $themes = explode(",", $_GET["themes"]);
    $themes = array_filter(array_map("intval", $themes));

    if (!empty($themes)) {
        $joinTheme = "JOIN menu_theme mt ON m.menu_id = mt.menu_id";
        $where[] = "mt.theme_id IN (" . implode(",", $themes) . ")";
    }
}

if (!empty($_GET["regimes"])) {
    $regimes = explode(",", $_GET["regimes"]);
    $regimes = array_filter(array_map("intval", $regimes));

    if (!empty($regimes)) {
        $where[] = "m.regime_id IN (" . implode(",", $regimes) . ")";
    }
}

$sql = "
    SELECT DISTINCT m.*
    FROM menu m
    $joinTheme
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menus = $stmt->fetchAll();

if (!$menus): ?>
    <p class="no-menu">Aucun menu ne correspond à votre recherche.</p>
<?php
    exit;
endif;

foreach ($menus as $m):

    $stmtR = $pdo->prepare("SELECT libelle FROM regime WHERE regime_id = ?");
    $stmtR->execute([$m["regime_id"]]);
    $regime = $stmtR->fetch();

    $stmtP = $pdo->prepare("
        SELECT p.titre_plat, p.photo
        FROM plat p
        JOIN menu_plat mp ON p.plat_id = mp.plat_id
        WHERE mp.menu_id = ?
    ");
    $stmtP->execute([$m["menu_id"]]);
    $plats = $stmtP->fetchAll();

    $stmtA = $pdo->prepare("
        SELECT DISTINCT a.libelle
        FROM allergene a
        JOIN plat_allergene pa ON a.allergene_id = pa.allergene_id
        JOIN menu_plat mp ON pa.plat_id = mp.plat_id
        WHERE mp.menu_id = ?
    ");
    $stmtA->execute([$m["menu_id"]]);
    $allergenes = $stmtA->fetchAll();
?>

<div class="menu-card">

    <div class="menu-card-main">
        <div class="menu-card-text">
            <h3><?= htmlspecialchars($m["titre"]) ?></h3>
            <p><?= htmlspecialchars($m["description"]) ?></p>
        </div>

        <button type="button" class="btn menu-toggle-btn" onclick="toggleMenu(<?= $m['menu_id'] ?>)">
            <span id="icon-<?= $m['menu_id'] ?>">+</span> Voir en détails
        </button>
    </div>

    <div id="menu-<?= $m['menu_id'] ?>" class="menu-detail" style="display:none;">

        <p><strong>Régime :</strong> <?= htmlspecialchars($regime["libelle"] ?? "Non renseigné") ?></p>

        <?php if ($plats): ?>
            <div class="menu-photos">
                <?php foreach ($plats as $p): ?>
                    <?php if (!empty($p["photo"])): ?>
                        <img
                            src="../../images/plat/<?= htmlspecialchars($p["photo"]) ?>"
                            alt="<?= htmlspecialchars($p["titre_plat"]) ?>"
                        >
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><strong>Le menu « <?= htmlspecialchars($m["titre"]) ?> » est composé de :</strong></p>

        <ul class="menu-list">
            <?php foreach ($plats as $p): ?>
                <li><?= htmlspecialchars($p["titre_plat"]) ?></li>
            <?php endforeach; ?>
        </ul>

        <p>
            <strong>Allergènes :</strong>
            <?php
            if ($allergenes) {
                echo htmlspecialchars(implode(", ", array_column($allergenes, "libelle")));
            } else {
                echo "Aucun";
            }
            ?>
        </p>

        <div class="menu-infos">
            <p> Pour <?= htmlspecialchars($m["nombre_personne_minimum"]) ?> participants</p>
            <p> Prix : <?= htmlspecialchars($m["prix_par_personne"]) ?> €</p>
            <p> Commande 10 jours avant</p>
            <p>Stock : <?= htmlspecialchars($m["quantite_restante"]) ?></p>
        </div>

        <div class="menu-action">
            <?php if (!isset($_SESSION["user_id"])): ?>
                <?php $_SESSION["redirect_after_login"] = "../commande/commande.php?menu_id=" . $m["menu_id"]; ?>
                <a class="btn" href="../auth/login.php">Commander</a>
            <?php else: ?>
                <a class="btn" href="../commande/commande.php?menu_id=<?= $m["menu_id"] ?>">Commander</a>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php endforeach; ?>