<?php
session_start();

// Destruye todas las variables de sesión
$_SESSION = [];

// Destruye la sesión completamente
session_destroy();

// Redirige al inicio de sesión
header("Location: index.php");
exit;
