<?php
session_start();
require_once "../../../backend/db.php";

$email = $_POST["email"];
$password = $_POST["password"];

if (
    strlen($password) < 10 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[!?_\-@&]/', $password)
) {
    $_SESSION["error"] = "Mot de passe non conforme : 10 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial (!?_-@&)";
    header("Location: employe_create.php");
    exit;
}

$check = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$check->execute([$email]);

if ($check->fetch()) {
    $_SESSION["error"] = " Email déjà utilisé";
    header("Location: employe_create.php");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO utilisateur (email, password, role_id, actif)
    VALUES (?, ?, 2, 1)
");
$stmt->execute([$email, $hash]);

mail($email, "Compte employé créé",
"Votre compte employé a été créé. Contactez l'administrateur pour vos accès.");

$_SESSION["success"] = " Employé créé avec succès";

header("Location: ../compte.php");
exit;