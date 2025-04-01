<?php

require '../../config/conexion.php';
require "./modelo_login.php";
session_start();
/**
 * ../../config/conexion.php -> importamos el objeto conexion de la db
 * ./modelo_login.php -> importamos el objeto consulta usuario y estudiantes
 * $conexion = $pdo->getConexion() -> obtenemos la conexion PDO
 */
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
        $get_user = new Modelo_login($conexion, $email);
        $get_user = $get_user->getuser(); //empaqueta el resultado de la consulta en como un array
        $get_estudent = new Modelo_login($conexion, $email);
        $get_estudent = $get_estudent->getAspirantes(); //empaqueta el resultado de la consulta en como un array

        if (!empty($get_user) || !empty($get_estudent)) {
            //validacion de usuario con rol (admin, docente)
            if ($email == $get_user['email'] && $password == $get_user['password'] ) {
                header("Location: ../admin/index_admin.php");
                $_SESSION['usuario_rol'] = $get_user['rol']; 
                // validacion de estudiante
            }else if ($email == $get_estudent['email'] && $password == $get_estudent['codigo_registro_examen']) {
                $_SESSION['estudiante_id'] = $get_estudent['numero_identificacion'];
                header("Location: ../aspirantes/preseleccion_de_examen.php");
            }
 
        } 
    } else {
        header('location: ./login.php');

    }
 
}





$pdo->closeConexion();
?>