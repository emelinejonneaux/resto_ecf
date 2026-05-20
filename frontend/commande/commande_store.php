<?php
session_start();
require_once "../../backend/db.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$menu_id = (int)($_POST["menu_id"] ?? 0);
$nombre_personne = (int)($_POST["nombre_personne"] ?? 0);
$date = $_POST["date_prestation"] ?? null;
$heure = $_POST["heure_livraison"] ?? null;
$adresse = $_POST["adresse_livraison"] ?? null;
$commentaire = $_POST["commentaire"] ?? null;
$livraison = isset($_POST["livraison"]);

$stmt = $pdo->prepare("SELECT * FROM menu WHERE menu_id = ?");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch();

if (!$menu) {
    $_SESSION["message"] = "❌ Menu invalide";
    header("Location: ../menus/menus.php");
    exit;
}

$prixMenu = $menu["prix_par_personne"] * $nombre_personne;

if ($nombre_personne >= $menu["nombre_personne_minimum"] + 5) {
    $prixMenu *= 0.9;
}

$prixLivraison = $livraison ? 5 : 0;

$totalMenu = $prixMenu + $prixLivraison;

$stmt = $pdo->prepare("
INSERT INTO commande (
    numero_commande,
    date_commande,
    date_prestation,
    heure_livraison,
    prix_menu,
    nombre_personne,
    prix_livraison,
    statut,
    utilisateur_id,
    menu_id,
    adresse_livraison
) VALUES (
    ?, NOW(), ?, ?, ?, ?, ?, 'en attente', ?, ?, ?
)
");

$numero = "CMD-" . time();

$stmt->execute([
    $numero,
    $date,
    $heure,
    $prixMenu,
    $nombre_personne,
    $prixLivraison,
    $user_id,
    $menu_id,
    $adresse
]);

$_SESSION["message"] =
" Votre commande a bien été enregistrée. Le mail récapitulatif n'a pas pu être envoyé.";

header("Location: ../compte/compte.php");
exit;