<?php
session_start();
require_once "../db.php";

$email = $_POST["email"];

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
$_SESSION["message"] = "Si un compte existe, un email de réinitialisation a été envoyé.";
} else {
    
    $_SESSION["message"] = "Si un compte existe, un email de réinitialisation a été envoyé.";
}

header("Location: ../../frontend/auth/forgot_password.php");
exit;