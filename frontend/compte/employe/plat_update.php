<?php
require_once "../../../backend/db.php";
session_start();
if ($_SESSION["role"] == 3) {
    exit;
}

$id = $_POST["id"];
$titre = $_POST["titre_plat"];

$sql = "UPDATE plat SET titre_plat = ? WHERE plat_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$titre, $id]);

header("Location: ../compte.php");
exit;