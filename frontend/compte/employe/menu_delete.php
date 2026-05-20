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

$menu_id = (int)($_POST["menu_id"] ?? 0);

if (!$menu_id) {
    $_SESSION["message"] = "Suppression impossible.";
    header("Location: ../compte.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM menu_theme WHERE menu_id = ?");
$stmt->execute([$menu_id]);

$stmt = $pdo->prepare("DELETE FROM menu_plat WHERE menu_id = ?");
$stmt->execute([$menu_id]);

$stmt = $pdo->prepare("DELETE FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);

$_SESSION["message"] = "Menu supprimé avec succès.";
header("Location: ../compte.php");
exit;