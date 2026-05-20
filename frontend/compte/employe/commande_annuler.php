<?php
session_start();
require_once "../../../backend/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}

if ($_SESSION["role"] != 2 && $_SESSION["role"] != 1) {
    die("Accès refusé");
}

$commande_id = (int)($_POST["commande_id"] ?? 0);
$motif = trim($_POST["motif_annulation"] ?? "");

if (!$commande_id || $motif === "") {
    $_SESSION["message"] = "Annulation impossible : motif manquant.";
    header("Location: ../compte.php");
    exit;
}

/* adapte les noms de colonnes si besoin */
$stmt = $pdo->prepare("
    UPDATE commande
    SET statut = 'annulée', motif_annulation = ?
    WHERE commande_id = ?
");
$stmt->execute([$motif, $commande_id]);

$_SESSION["message"] = "Commande annulée avec succès.";
header("Location: ../compte.php");
exit;