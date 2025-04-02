<?php

session_start();
error_reporting(0);
$versesion = $_SESSION['usuario_rol'];
$versesionStudent = $_SESSION['numero_identificacion'];

if ($versesion == '' || $versesion == null) {
    header('location: ../login/login.php');
    die();
}
if ($versesion == 'admin') {
    header('../admin/index_admin.php');
    die();
}
if ($versesion == 'docente') {
    header('../examinador/index_examinador.php');
    die();
}
if (isset($versesionStudent)) {
    header('location: ../aspirantes/preseleccion_de_examen.php');
    die();
}

?>