<?php
require_once __DIR__ . "/../../backend/db.php";

$cmd = $pdo->query("
    SELECT m.menu_id, m.titre, COUNT(c.commande_id) AS total
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    GROUP BY c.menu_id, m.titre
    ORDER BY m.titre
")->fetchAll();

$labelsCmd = [];
$dataCmd = [];

foreach ($cmd as $c) {
    $labelsCmd[] = $c["titre"];
    $dataCmd[] = $c["total"];
}

$filtre_menu_id = (int)($_GET["menu_id"] ?? 0);
$filtre_debut = $_GET["debut"] ?? "";
$filtre_fin = $_GET["fin"] ?? "";

$sqlCA = "
    SELECT m.titre, SUM(c.prix_menu + c.prix_livraison) AS total
    FROM commande c
    JOIN menu m ON c.menu_id = m.menu_id
    WHERE 1=1
";

$paramsCA = [];

if (!empty($filtre_debut) && !empty($filtre_fin)) {
    $sqlCA .= " AND c.date_commande BETWEEN ? AND ?";
    $paramsCA[] = $filtre_debut;
    $paramsCA[] = $filtre_fin;
}

if ($filtre_menu_id > 0) {
    $sqlCA .= " AND c.menu_id = ?";
    $paramsCA[] = $filtre_menu_id;
}

$sqlCA .= " GROUP BY c.menu_id, m.titre ORDER BY m.titre";

$stmtCA = $pdo->prepare($sqlCA);
$stmtCA->execute($paramsCA);
$ca = $stmtCA->fetchAll();

$labelsCA = [];
$dataCA = [];

foreach ($ca as $c) {
    $labelsCA[] = $c["titre"];
    $dataCA[] = $c["total"];
}

$employes = $pdo->query("
    SELECT *
    FROM utilisateur
    WHERE role_id = 2
    ORDER BY nom, prenom
")->fetchAll();

$menus = $pdo->query("SELECT * FROM menu ORDER BY titre")->fetchAll();
?>

<div class="dashboard-wrapper admin-space">

    <h2 class="dashboard-page-title">Espace Admin</h2>

    <?php include "employe.php"; ?>

    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('employes')">
            <span>Employés</span>
            <span id="arrow-employes">▼</span>
        </button>

        <div id="employes" class="dashboard-content">

            <div class="dashboard-actions">
                <a href="admin/employe_create.php" class="btn">Créer un employé</a>
            </div>

            <?php if (empty($employes)): ?>
                <p>Aucun employé trouvé.</p>
            <?php else: ?>
                <?php foreach ($employes as $e): ?>
                    <div class="dashboard-card">

                        <p class="dashboard-title">
                            <b><?= htmlspecialchars($e["prenom"]) ?> <?= htmlspecialchars($e["nom"]) ?></b>
                        </p>

                        <p><b>Email :</b> <?= htmlspecialchars($e["email"]) ?></p>
                        <p>
                            <b>Statut :</b>
                            <?= $e["actif"] ? " Actif" : " Inactif" ?>
                        </p>

                        <div class="dashboard-actions">
                            <?php if ((int)$e["actif"] === 1): ?>
                                <a href="admin/employe_disable.php?id=<?= (int)$e["utilisateur_id"] ?>" class="btn">
                                    Désactiver
                                </a>
                            <?php else: ?>
                                <a href="admin/employe_enable.php?id=<?= (int)$e["utilisateur_id"] ?>" class="btn">
                                    Réactiver
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('stats')">
            <span> Statistiques</span>
            <span id="arrow-stats">▼</span>
        </button>

        <div id="stats" class="dashboard-content">

            <div class="dashboard-card">
                <h3 class="dashboard-block-title">Commandes par menu</h3>
                <div class="chart-box">
                    <canvas id="chartCmd"></canvas>
                </div>
            </div>

            <div class="dashboard-card">
                <h3 class="dashboard-block-title">Chiffre d’affaires</h3>

                <form method="GET" action="" class="dashboard-filter-form">
                    <div class="dashboard-filter-grid">
                        <div>
                            <label for="menu_id">Menu</label>
                            <select name="menu_id" id="menu_id">
                                <option value="">Tous les menus</option>
                                <?php foreach ($menus as $m): ?>
                                    <option value="<?= (int)$m["menu_id"] ?>" <?= ($filtre_menu_id === (int)$m["menu_id"]) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($m["titre"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="debut">Date début</label>
                            <input type="date" name="debut" id="debut" value="<?= htmlspecialchars($filtre_debut) ?>">
                        </div>

                        <div>
                            <label for="fin">Date fin</label>
                            <input type="date" name="fin" id="fin" value="<?= htmlspecialchars($filtre_fin) ?>">
                        </div>
                    </div>

                    <div class="dashboard-actions">
                        <button type="submit" class="btn">Filtrer</button>
                        <a href="" class="btn">Réinitialiser</a>
                    </div>
                </form>

                <div class="chart-box">
                    <canvas id="chartCA"></canvas>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function toggleAcc(id) {
    const el = document.getElementById(id);
    const arrow = document.getElementById("arrow-" + id);

    if (!el) return;

    el.classList.toggle("open");
    arrow.textContent = el.classList.contains("open") ? "▲" : "▼";
}

const ctxCmd = document.getElementById('chartCmd');
if (ctxCmd) {
    new Chart(ctxCmd, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labelsCmd) ?>,
            datasets: [{
                label: 'Nombre de commandes',
                data: <?= json_encode($dataCmd) ?>
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

const ctxCA = document.getElementById('chartCA');
if (ctxCA) {
    new Chart(ctxCA, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labelsCA) ?>,
            datasets: [{
                label: 'Chiffre d’affaires (€)',
                data: <?= json_encode($dataCA) ?>
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}
</script>