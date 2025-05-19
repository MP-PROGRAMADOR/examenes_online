<?php
// ../includes/conexion.php

$host = 'localhost';
$db   = 'web_examenes';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Excepciones ante errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Datos como arreglo asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva emulación de consultas
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Puedes registrar el error y mostrar un mensaje amigable
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}
