<?php
// Configuración de la base de datos
require '../../config/conexion.php'; // Conexión a la BD
require "./modelo_login.php";

$conexion = $pdo->getConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //recepcion de datos del form
    $email = $_POST["email"];
    $password = $_POST["password"];
    $email = trim($email);
    $password = trim($password);

    // Consulta SQL para buscar al usuario
    if (!empty($email) && !empty($password)) {
        //SERIALIZAMOS CONTRA ataques XSS
        $email = htmlspecialchars($email);
        $password = htmlspecialchars($password);

        //llamar a la funcion optener usuarios del modelo_login
        $get_user = new Modelo_login($conexion);
        $get_user = $get_user->getuser();


        foreach ($get_user as $row) {
            echo "Email: " . $row['email'] . ", Password: " . $row["password"] . ", Rol: " . $row["rol"];
            if ($email == $row['email'] && $password == $row['password'] && $row['rol'] == "admin") {
                header("Location: ../admin/index_admin.php");
            } else if ($email == $row['email'] && $password == $row['password'] && $row['role'] == "docente") {
                header("Location: ../examinador/index_examinador.php");
            }  
        }

        //busqueda de un estudiante 
        $get_estudent = new Modelo_login($conexion);
        $get_estudent = $get_estudent->getAspirantes() ;
        foreach ($get_estudent as $row) {
            echo "Email: " . $row['email'] . ", codigo de registro: " . $row["codigo_registro_examen"];
            if ($email == $row['email'] && $password == $row['codigo_registro_examen']  ) {
                header("Location: ../aspirantes/preseleccion_de_examen.php");
            }  
        }



        /**
         * INICIO CREDENCIALES ADMINISTRADOR
         */
        //credenciales para usuario admin
        // if ($email == 'admin@gmail.com') {
        //     header('Location: ../admin/index_admin.php');
        // }

        //FIN CREDENCIALES ADMIN


        /**
         * INICIO CREDENCIALES EXAMINADOR
         */

        // if ($email == 'examinador@gmail.com') {
        //     header('Location: ../examinador/index_examinador.php');
        //  }

        //FIN CREDENCIALES DE EXAMINADOR


        /**
         * INICIO CREDENNCIALES USUARIO
         */

        // if ($email == 'usuario@gmail.com') {
        //claves de acceso al examen validos
        //    header("Location: ../aspirantes/preseleccion_de_examen.php");
        //    exit(); // Importante: detener la ejecución del script después de la redirección

        // }
        // if ($email == 'sir@gmail.com') {
        //ya ha agotado sus intentos de acceso al examen
        //     header("Location: ../aspirantes/intentos_agotados.php");
        //     exit(); // Importante: detener la ejecución del script después de la redirección
        //  }

        //Fin DEL PROSAMIENTO DE LOGIN USUARIO
    }
}





$pdo->closeConexion();
?>