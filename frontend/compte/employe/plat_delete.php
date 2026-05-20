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

$plat_id = (int)($_POST["plat_id"] ?? 0);

if (!$plat_id) {
    $_SESSION["message"] = "Suppression impossible.";
    header("Location: ../compte.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM plat_allergene WHERE plat_id = ?");
$stmt->execute([$plat_id]);

$stmt = $pdo->prepare("DELETE FROM menu_plat WHERE plat_id = ?");
$stmt->execute([$plat_id]);

$stmt = $pdo->prepare("DELETE FROM plat WHERE plat_id = ?");
$stmt->execute([$plat_id]);

$_SESSION["message"] = "Plat supprimé avec succès.";
header("Location: ../compte.php");
exit;