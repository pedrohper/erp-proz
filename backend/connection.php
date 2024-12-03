<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "dbzbt32k20";
$dbName = "workvision";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", "$dbUser", "$dbPass");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}

catch (PDOException $e) {
    echo $e->getMessage();
}