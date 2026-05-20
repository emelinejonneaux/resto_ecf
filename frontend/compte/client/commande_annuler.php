<?php
session_start();
require_once "../../../backend/db.php";

$user_id = $_SESSION["user_id"];
$commande_id = $_POST["commande_id"];

$stmt = $pdo->prepare("
    SELECT statut 
    FROM commande 
    WHERE commande_id = ? AND utilisateur_id = ?
");
$stmt->execute([$commande_id, $user_id]);
$cmd = $stmt->fetch();

if ($cmd && $cmd["statut"] != "accepté") {

    $stmt = $pdo->prepare("
        UPDATE commande 
        SET statut = 'annulée'
        WHERE commande_id = ? AND utilisateur_id = ?
    ");

    $stmt->execute([$commande_id, $user_id]);
}

header("Location: ../compte.php");
exit;