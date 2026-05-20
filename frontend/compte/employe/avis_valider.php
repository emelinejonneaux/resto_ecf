<?php
require_once "../../../backend/db.php";
session_start();
if ($_SESSION["role"] == 3) {
    exit;
}

$id = $_GET["id"];

$stmt = $pdo->prepare("UPDATE avis SET statut = 'validé' WHERE avis_id = ?");
$stmt->execute([$id]);

header("Location: ../compte.php");
exit;