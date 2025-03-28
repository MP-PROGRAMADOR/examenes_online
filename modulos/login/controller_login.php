<?php
// Configuración de la base de datos
include '../conexion/conexion.php'; // Conexión a la BD


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Consulta SQL para buscar al usuario
    if (!empty($email) && !empty($password)) {

        /**
         * INICIO CREDENCIALES ADMINISTRADOR
         */
        //credenciales para usuario admin
        if ($email == 'admin@gmail.com') {
            header('Location: ../admin/index_admin.php');
        }
        
        //FIN CREDENCIALES ADMIN


        /**
         * INICIO CREDENCIALES EXAMINADOR
         */
 
        if ($email == 'examinador@gmail.com') {
            header('Location: ../examinador/index_examinador.php');
        }

        //FIN CREDENCIALES DE EXAMINADOR


        /**
         * INICIO CREDENNCIALES USUARIO
         */
        
        if ($email == 'usuario@gmail.com') {
            //claves de acceso al examen validos
            header("Location: ../aspirantes/preseleccion_de_examen.php");
            exit(); // Importante: detener la ejecución del script después de la redirección
            
        }
        if ($email == 'sir@gmail.com') {
            //ya ha agotado sus intentos de acceso al examen
            header("Location: ../aspirantes/intentos_agotados.php");
            exit(); // Importante: detener la ejecución del script después de la redirección
        }

        //Fin DEL PROSAMIENTO DE LOGIN USUARIO
    }
}





$conn->close();
?>