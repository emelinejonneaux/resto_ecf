<?php

if (getenv("JAWSDB_URL")) {
    $url = parse_url(getenv("JAWSDB_URL"));

    $host = $url["host"];
    $dbname = substr($url["path"], 1);
    $username = $url["user"];
    $password = $url["pass"];
    $port = $url["port"] ?? 3306;

} else {
    $host = "localhost";
    $dbname = "ecf";
    $username = "root";
    $password = "";
    $port = 3306;
}

try {

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Erreur connexion DB : " . $e->getMessage());

}