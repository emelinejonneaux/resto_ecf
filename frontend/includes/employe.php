<?php
require_once "../../backend/db.php";

$filtre_statut = trim($_GET["statut"] ?? "");
$filtre_client = trim($_GET["client"] ?? "");

$sql = "
    SELECT c.*, u.nom, u.prenom
    FROM commande c
    JOIN utilisateur u ON c.utilisateur_id = u.utilisateur_id
    WHERE 1=1
";

$params = [];

if ($filtre_statut !== "") {
    $sql .= " AND c.statut = ?";
    $params[] = $filtre_statut;
}

if ($filtre_client !== "") {
    $sql .= " AND (u.nom LIKE ? OR u.prenom LIKE ?)";
    $params[] = "%" . $filtre_client . "%";
    $params[] = "%" . $filtre_client . "%";
}

$sql .= " ORDER BY c.date_commande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$commandes = $stmt->fetchAll();

$horaires = $pdo->query("SELECT * FROM horaire ORDER BY horaire_id ASC")->fetchAll();

$avis = $pdo->query("
    SELECT a.*, u.nom, u.prenom
    FROM avis a
    JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
    ORDER BY a.avis_id DESC
")->fetchAll();

$menus = $pdo->query("
    SELECT *
    FROM menu
    ORDER BY menu_id DESC
")->fetchAll();

$plats = $pdo->query("
    SELECT *
    FROM plat
    ORDER BY plat_id DESC
")->fetchAll();

$statuts_commandes = [
    "en attente",
    "accepté",
    "en préparation",
    "en cours de livraison",
    "livré",
    "en attente du retour de matériel",
    "terminée",
    "annulée"
];
?>

<div class="dashboard-wrapper">
    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('commandes')">
            <span>📦 Commandes</span>
            <span id="arrow-commandes">▼</span>
        </button>

        <div id="commandes" class="dashboard-content">

            <form method="GET" action="" class="dashboard-filter-form">
                <div class="dashboard-filter-grid">
                    <div>
                        <label for="client">Nom du client</label>
                        <input
                            type="text"
                            id="client"
                            name="client"
                            value="<?= htmlspecialchars($filtre_client) ?>"
                            placeholder="Nom ou prénom"
                        >
                    </div>

                    <div>
                        <label for="statut">Statut</label>
                        <select id="statut" name="statut">
                            <option value="">Tous les statuts</option>
                            <?php foreach ($statuts_commandes as $statut): ?>
                                <option value="<?= htmlspecialchars($statut) ?>" <?= $filtre_statut === $statut ? "selected" : "" ?>>
                                    <?= htmlspecialchars($statut) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="dashboard-actions">
                    <button type="submit" class="btn">Filtrer</button>
                    <a href="" class="btn">Réinitialiser</a>
                </div>
            </form>

            <?php if (empty($commandes)): ?>
                <p>Aucune commande trouvée.</p>
            <?php else: ?>
                <?php foreach ($commandes as $c): ?>
                    <div class="dashboard-card">

                        <p class="dashboard-title">
                            <b>#<?= htmlspecialchars($c["numero_commande"]) ?></b>
                        </p>

                        <p><b>Client :</b> <?= htmlspecialchars($c["prenom"]) ?> <?= htmlspecialchars($c["nom"]) ?></p>
                        <p><b>Statut :</b> <?= htmlspecialchars($c["statut"]) ?></p>
                        <p><b>Date prestation :</b> <?= htmlspecialchars($c["date_prestation"]) ?> <?= htmlspecialchars($c["heure_livraison"]) ?></p>
                        <p><b>Adresse :</b> <?= htmlspecialchars($c["adresse_livraison"]) ?> - <?= htmlspecialchars($c["ville_livraison"]) ?></p>
                        <p><b>Prix menu :</b> <?= htmlspecialchars($c["prix_menu"]) ?> €</p>
                        <p><b>Livraison :</b> <?= htmlspecialchars($c["prix_livraison"]) ?> €</p>

                        <form action="employe/commande_statut.php" method="POST" class="dashboard-form">
                            <input type="hidden" name="commande_id" value="<?= (int)$c["commande_id"] ?>">

                            <label for="statut-<?= (int)$c["commande_id"] ?>">Mettre à jour le statut</label>
                            <select name="statut" id="statut-<?= (int)$c["commande_id"] ?>" required>
                                <?php foreach ($statuts_commandes as $statut): ?>
                                    <?php if ($statut !== "annulée"): ?>
                                        <option value="<?= htmlspecialchars($statut) ?>" <?= $c["statut"] === $statut ? "selected" : "" ?>>
                                            <?= htmlspecialchars($statut) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="btn">
                                Mettre à jour
                            </button>
                        </form>

                        <?php if ($c["statut"] !== "annulée" && $c["statut"] !== "terminée"): ?>
                            <form action="employe/commande_annuler.php" method="POST" class="dashboard-form">
                                <input type="hidden" name="commande_id" value="<?= (int)$c["commande_id"] ?>">

                                <label for="motif-<?= (int)$c["commande_id"] ?>">Motif d'annulation</label>
                                <textarea
                                    id="motif-<?= (int)$c["commande_id"] ?>"
                                    name="motif_annulation"
                                    placeholder="Indiquez le motif..."
                                    required
                                ></textarea>

                                <button type="submit" class="btn">
                                    Annuler la commande
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('horaires')">
            <span> Horaires</span>
            <span id="arrow-horaires">▼</span>
        </button>

        <div id="horaires" class="dashboard-content">
            <?php if (empty($horaires)): ?>
                <p>Aucun horaire enregistré.</p>
            <?php else: ?>
                <?php foreach ($horaires as $h): ?>
                    <div class="dashboard-card">
                        <p class="dashboard-title">
                            <b><?= htmlspecialchars($h["jour"]) ?></b>
                        </p>

                        <p><?= htmlspecialchars($h["heure_ouverture"]) ?> → <?= htmlspecialchars($h["heure_fermeture"]) ?></p>

                        <div class="dashboard-actions">
                            <a class="btn" href="employe/horaire_edit.php?id=<?= (int)$h["horaire_id"] ?>">
                                Modifier
                            </a>

                            <form action="employe/horaire_delete.php" method="POST" class="dashboard-inline-form" onsubmit="return confirm('Supprimer cet horaire ?')">
                                <input type="hidden" name="horaire_id" value="<?= (int)$h["horaire_id"] ?>">
                                <button type="submit" class="btn">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('avis')">
            <span> Avis</span>
            <span id="arrow-avis">▼</span>
        </button>

        <div id="avis" class="dashboard-content">
            <?php if (empty($avis)): ?>
                <p>Aucun avis pour le moment.</p>
            <?php else: ?>
                <?php foreach ($avis as $a): ?>
                    <div class="dashboard-card">
                        <p class="dashboard-title">
                            <b><?= htmlspecialchars($a["nom"]) ?> <?= htmlspecialchars($a["prenom"]) ?></b>
                        </p>

                        <p><b>Note :</b> ⭐ <?= htmlspecialchars($a["note"]) ?>/5</p>
                        <p><?= htmlspecialchars($a["description"]) ?></p>
                        <p><b>Statut :</b> <?= htmlspecialchars($a["statut"]) ?></p>

                        <?php if ($a["statut"] === "en attente"): ?>
                            <div class="dashboard-actions">
                                <a class="btn" href="employe/avis_valider.php?id=<?= (int)$a["avis_id"] ?>">
                                    Valider
                                </a>

                                <a class="btn" href="employe/avis_refuser.php?id=<?= (int)$a["avis_id"] ?>">
                                    Refuser
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('menus')">
            <span> Menus</span>
            <span id="arrow-menus">▼</span>
        </button>

        <div id="menus" class="dashboard-content">
            <?php if (empty($menus)): ?>
                <p>Aucun menu enregistré.</p>
            <?php else: ?>
                <?php foreach ($menus as $m): ?>
                    <div class="dashboard-card">
                        <p class="dashboard-title">
                            <b><?= htmlspecialchars($m["titre"]) ?></b>
                        </p>

                        <p><b>Description :</b> <?= htmlspecialchars($m["description"]) ?></p>
                        <p><b>Prix / personne :</b> <?= htmlspecialchars($m["prix_par_personne"]) ?> €</p>
                        <p><b>Nombre minimum :</b> <?= htmlspecialchars($m["nombre_personne_minimum"]) ?></p>
                        <p><b>Stock :</b> <?= htmlspecialchars($m["quantite_restante"]) ?></p>

                        <div class="dashboard-actions">
                            <a class="btn" href="employe/menu_edit.php?id=<?= (int)$m["menu_id"] ?>">
                                Modifier
                            </a>

                            <form action="employe/menu_delete.php" method="POST" class="dashboard-inline-form" onsubmit="return confirm('Supprimer ce menu ?')">
                                <input type="hidden" name="menu_id" value="<?= (int)$m["menu_id"] ?>">
                                <button type="submit" class="btn">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-section">
        <button class="dashboard-toggle" type="button" onclick="toggleAcc('plats')">
            <span> Plats</span>
            <span id="arrow-plats">▼</span>
        </button>

        <div id="plats" class="dashboard-content">
            <?php if (empty($plats)): ?>
                <p>Aucun plat enregistré.</p>
            <?php else: ?>
                <?php foreach ($plats as $p): ?>
                    <div class="dashboard-card">
                        <p class="dashboard-title">
                            <b><?= htmlspecialchars($p["titre_plat"]) ?></b>
                        </p>

                        <?php if (!empty($p["description"])): ?>
                            <p><b>Description :</b> <?= htmlspecialchars($p["description"]) ?></p>
                        <?php endif; ?>

                        <div class="dashboard-actions">
                            <a class="btn" href="employe/plat_edit.php?id=<?= (int)$p["plat_id"] ?>">
                                Modifier
                            </a>

                            <form action="employe/plat_delete.php" method="POST" class="dashboard-inline-form" onsubmit="return confirm('Supprimer ce plat ?')">
                                <input type="hidden" name="plat_id" value="<?= (int)$p["plat_id"] ?>">
                                <button type="submit" class="btn">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
function toggleAcc(id) {
    const content = document.getElementById(id);
    const arrow = document.getElementById("arrow-" + id);

    if (!content) return;

    content.classList.toggle("open");
    arrow.textContent = content.classList.contains("open") ? "▲" : "▼";
}
</script>