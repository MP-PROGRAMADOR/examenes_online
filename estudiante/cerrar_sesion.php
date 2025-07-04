<?php
session_start();

// 1. Eliminar todas las variables de sesión
$_SESSION = [];

// 2. Si se usa una cookie de sesión, eliminarla del navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),        // Nombre de la cookie de sesión
        '',                    // Valor vacío
        time() - 42000,        // Expira en el pasado
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Finalmente, destruir la sesión en el servidor
session_destroy();

// 4. Redirigir al login u otra página
header("Location: index.php");
exit;

