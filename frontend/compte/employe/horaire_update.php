<?php
require_once "../../../backend/db.php";
session_start();
if ($_SESSION["role"] == 3) {
    exit;
}

$id = $_POST["id"];
$jour = $_POST["jour"];
$ouverture = $_POST["ouverture"];
$fermeture = $_POST["fermeture"];

$sql = "UPDATE horaire 
        SET jour = ?, heure_ouverture = ?, heure_fermeture = ?
        WHERE horaire_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$jour, $ouverture, $fermeture, $id]);

header("Location: ../compte.php");
exit;