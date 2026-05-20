<?php
session_start();
require_once "../db.php";

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if (empty($email) || empty($password)) {
    $_SESSION["message"] = " Veuillez remplir tous les champs";
    header("Location: ../../frontend/auth/login.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION["message"] = " Email ou mot de passe incorrect";
    header("Location: ../../frontend/auth/login.php");
    exit;
}

if ((int)$user["actif"] === 0) {
    $_SESSION["message"] = "Compte désactivé";
    header("Location: ../../frontend/auth/login.php");
    exit;
}

if (!password_verify($password, $user["password"])) {
    $_SESSION["message"] = " Email ou mot de passe incorrect";
    header("Location: ../../frontend/auth/login.php");
    exit;
}
$_SESSION["user_id"] = $user["utilisateur_id"];
$_SESSION["role"] = $user["role_id"];
$_SESSION["prenom"] = $user["prenom"];

if (!empty($_SESSION["redirect_after_login"])) {

    $redirect = $_SESSION["redirect_after_login"];
    unset($_SESSION["redirect_after_login"]);

    header("Location: $redirect");
    exit;
}

header("Location: ../../frontend/compte/compte.php");
exit;