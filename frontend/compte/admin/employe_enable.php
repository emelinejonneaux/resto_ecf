<?php
require_once "../../../backend/db.php";

$id = $_GET["id"];

$stmt = $pdo->prepare("
    UPDATE utilisateur 
    SET actif = 1 
    WHERE utilisateur_id = ?
");
$stmt->execute([$id]);

header("Location: ../compte.php");
exit;