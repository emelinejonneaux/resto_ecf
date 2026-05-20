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

$nom = $_POST["nom"];
$prenom = $_POST["prenom"];
$telephone = $_POST["telephone"];
$adresse = $_POST["adresse"];
$ville = $_POST["ville"];
$pays = $_POST["pays"];

$stmt = $pdo->prepare("
    UPDATE utilisateur 
    SET nom = ?, prenom = ?, telephone = ?, adresse = ?, ville = ?, pays = ?
    WHERE utilisateur_id = ?
");

$stmt->execute([
    $nom,
    $prenom,
    $telephone,
    $adresse,
    $ville,
    $pays,
    $user_id
]);

$_SESSION["prenom"] = $prenom;

header("Location: ../compte.php");
exit;