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

$horaire_id = (int)($_POST["horaire_id"] ?? 0);

if (!$horaire_id) {
    $_SESSION["message"] = "Suppression impossible.";
    header("Location: ../compte.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM horaire WHERE horaire_id = ?");
$stmt->execute([$horaire_id]);

$_SESSION["message"] = "Horaire supprimé avec succès.";
header("Location: ../compte.php");
exit;