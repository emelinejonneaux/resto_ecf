<?php
require_once "../../../backend/db.php";
session_start();
if ($_SESSION["role"] == 3) {
    exit;
}

$id = $_POST["id"];
$titre = $_POST["titre"];
$description = $_POST["description"];
$min = $_POST["nombre_personne_minimum"];
$prix = $_POST["prix_par_personne"];
$stock = $_POST["quantite_restante"];

$pdo->prepare("
    UPDATE menu 
    SET titre=?,
        description=?,
        nombre_personne_minimum=?,
        prix_par_personne=?,
        quantite_restante=?
    WHERE menu_id=?
")->execute([$titre, $description, $min, $prix, $stock, $id]);

header("Location: ../compte.php");
exit;