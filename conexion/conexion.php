<?php
// Incluimos el archivo de configuración
include_once 'config.php';

// Habilitar el modo de error para depuración si está en desarrollo
if (DEBUG_MODE) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

try {
    // Conexión a la base de datos usando MySQLi con conexión persistente
    $conn = new mysqli("p:" . DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Verificar la conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Configurar la codificación de caracteres
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    // Manejo de errores (podemos registrarlos en un log)
    error_log($e->getMessage());
    if (DEBUG_MODE) {
        die("Error de conexión: " . $e->getMessage());
    } else {
        die("Error al conectar con la base de datos. Contacte al administrador.");
    }
}
?>
