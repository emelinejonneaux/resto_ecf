<?php
session_start();
require_once "../db.php";
$nom = trim($_POST["nom"]);
$prenom = trim($_POST["prenom"]);
$telephone = trim($_POST["telephone"]);
$email = trim($_POST["email"]);
$adresse = trim($_POST["adresse"]);
$ville = trim($_POST["ville"]);
$pays = trim($_POST["pays"]);
$password = $_POST["password"];

if (strlen($password) < 10) {
    $_SESSION["message"] = "Mot de passe non conforme : minimum 10 caractères.";
    header("Location: ../../frontend/auth/register.php");
    exit;
}

if (!preg_match('/[A-Z]/', $password)) {
    $_SESSION["message"] = "Mot de passe non conforme : au moins 1 majuscule.";
    header("Location: ../../frontend/auth/register.php");
    exit;
}

if (!preg_match('/[a-z]/', $password)) {
    $_SESSION["message"] = "Mot de passe non conforme : au moins 1 minuscule.";
    header("Location: ../../frontend/auth/register.php");
    exit;
}

if (!preg_match('/[0-9]/', $password)) {
    $_SESSION["message"] = "Mot de passe non conforme : au moins 1 chiffre.";
    header("Location: ../../frontend/auth/register.php");
    exit;
}

if (!preg_match('/[!\?_\-@&]/', $password)) {
    $_SESSION["message"] = "Mot de passe non conforme : au moins 1 caractère spécial (!?_-@&).";
    header("Location: ../../frontend/auth/register.php");
    exit;
}

$stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    $_SESSION["message"] = "Cet email est déjà utilisé.";
    header("Location: ../../frontend/auth/register.php");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO utilisateur 
    (email, password, prenom, nom, telephone, ville, pays, adresse, role_id, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 3, NOW())
");

$stmt->execute([
    $email,
    $hash,
    $prenom,
    $nom,
    $telephone,
    $ville,
    $pays,
    $adresse
]);

mail($email, "Bienvenue", "Votre compte a été créé avec succès !");

header("Location: ../../frontend/auth/success_register.php");
exit;