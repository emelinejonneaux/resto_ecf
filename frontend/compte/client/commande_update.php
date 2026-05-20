<?php
session_start();
require_once "../../../backend/db.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}
if ($_SESSION["role"] != 3) {
    die("Accès refusé");
}
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    UPDATE commande 
    SET date_prestation = ?,
        heure_livraison = ?,
        adresse_livraison = ?,
        ville_livraison = ?,
        nombre_personne = ?
    WHERE commande_id = ? AND utilisateur_id = ?
");

$stmt->execute([
    $_POST["date_prestation"],
    $_POST["heure_livraison"],
    $_POST["adresse_livraison"],
    $_POST["ville_livraison"],
    $_POST["nombre_personne"],
    $_POST["commande_id"],
    $user_id
]);

header("Location: ../compte.php");
exit;