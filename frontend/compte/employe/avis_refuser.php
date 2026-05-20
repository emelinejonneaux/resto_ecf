<?php
session_start();
require_once "../../../backend/db.php";
if ($_SESSION["role"] == 3) {
    exit;
}

$id = $_GET["id"];

$stmt = $pdo->prepare("UPDATE avis SET statut = 'refusé' WHERE avis_id = ?");
$stmt->execute([$id]);

header("Location: ../compte.php");
exit;