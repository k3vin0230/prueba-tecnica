<?php
$host = 'localhost';
$db   = 'prueba';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Conecxion fallida con la base de datos"]);
    exit;
}
