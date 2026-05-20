<?php
session_start();
require_once "../../../backend/db.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit;
}
if ($_SESSION["role"] != 3) {
    die("Accès refusé");
}
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    INSERT INTO avis (note, description, statut, utilisateur_id)
    VALUES (?, ?, 'en attente', ?)
");

$stmt->execute([
    $_POST["note"],
    $_POST["description"],
    $user_id
]);

header("Location: ../compte.php");
exit;