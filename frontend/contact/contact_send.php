<?php
session_start();

$titre = htmlspecialchars(trim($_POST["titre"]));
$description = htmlspecialchars(trim($_POST["description"]));
$email = htmlspecialchars(trim($_POST["email"]));

if (empty($titre) || empty($description) || empty($email)) {
    $_SESSION["error"] = "Tous les champs sont obligatoires";
    header("Location: contact.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"] = " Email invalide";
    header("Location: contact.php");
    exit;
}

$to = ""; 
$subject = "Nouveau message contact : " . $titre;

$message = "
Titre : $titre

Message :
$description

Email : $email
";

$headers = "From: $email";
if (mail($to, $subject, $message, $headers)) {
    $_SESSION["message"] = " Message envoyé avec succès";
} else {
    $_SESSION["error"] = "Erreur lors de l'envoi du mail";
}

header("Location: contact.php");
exit;