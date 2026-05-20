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
$statut = trim($_POST["statut"] ?? "");

$statuts_autorises = [
    "en attente",
    "accepté",
    "en préparation",
    "en cours de livraison",
    "livré",
    "en attente du retour de matériel",
    "terminée"
];

if (!$commande_id || !in_array($statut, $statuts_autorises, true)) {
    $_SESSION["message"] = "Mise à jour impossible.";
    header("Location: ../compte.php");
    exit;
}

$stmt = $pdo->prepare("UPDATE commande SET statut = ? WHERE commande_id = ?");
$stmt->execute([$statut, $commande_id]);

$_SESSION["message"] = "Statut mis à jour avec succès.";
header("Location: ../compte.php");
exit;