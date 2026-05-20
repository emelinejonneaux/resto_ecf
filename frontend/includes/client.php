<?php
require_once "../../backend/db.php";

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT * FROM commande
    WHERE utilisateur_id = ?
    ORDER BY date_commande DESC
");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll();
?>

<div class="client-space">

    <h2>Espace Client</h2>

    <div class="client-section">
        <h3>Mes informations</h3>

        <div class="client-infos">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user["nom"]) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user["prenom"]) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user["email"]) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user["telephone"]) ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($user["adresse"]) ?></p>
            <p><strong>Ville :</strong> <?= htmlspecialchars($user["ville"]) ?></p>
            <p><strong>Pays :</strong> <?= htmlspecialchars($user["pays"]) ?></p>
        </div>

        <div class="client-actions">
            <a href="client/profil_edit.php" class="btn">Modifier mes infos</a>
        </div>
    </div>

    <div class="client-section">
        <h3>Historique commandes</h3>

        <?php if (empty($commandes)): ?>
            <p>Vous n'avez encore passé aucune commande.</p>
        <?php else: ?>
            <div class="client-commandes">
                <?php foreach ($commandes as $c): ?>
                    <div class="commande-item">

                        <p class="commande-numero">
                            <strong>#<?= htmlspecialchars($c["numero_commande"]) ?></strong>
                        </p>

                        <p><strong>Statut :</strong> <?= htmlspecialchars($c["statut"]) ?></p>
                        <p><strong>Date prestation :</strong> <?= htmlspecialchars($c["date_prestation"]) ?> à <?= htmlspecialchars($c["heure_livraison"]) ?></p>
                        <p><strong>Adresse :</strong> <?= htmlspecialchars($c["adresse_livraison"]) ?> - <?= htmlspecialchars($c["ville_livraison"]) ?></p>
                        <p><strong>Prix menu :</strong> <?= htmlspecialchars($c["prix_menu"]) ?> €</p>
                        <p><strong>Livraison :</strong> <?= htmlspecialchars($c["prix_livraison"]) ?> €</p>

                        <div class="commande-actions">
                            <?php if ($c["statut"] == "en attente"): ?>
                                <a href="client/commande_edit.php?id=<?= $c["commande_id"] ?>" class="btn">Modifier</a>

                                <form action="client/commande_annuler.php" method="POST" class="commande-form-inline">
                                    <input type="hidden" name="commande_id" value="<?= $c["commande_id"] ?>">
                                    <button type="submit" class="btn">Annuler la commande</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($c["statut"] == "terminée"): ?>
                                <a href="client/avis.php?id=<?= $c["commande_id"] ?>" class="btn">Donner un avis</a>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>