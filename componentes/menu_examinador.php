<?php

require "../formularios/formulario_registro_examen.php";
?>

<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">
                <img class="img-80 img-radius" src="../assets/images/avatar-4.png" alt="User-Profile-Image">
                <div class="user-details">
                    <span id="more-details">Examinador<i class="fa fa-caret-down"></i></span>
                </div>
            </div>

            <div class="main-menu-content">
                <ul>
                    <li class="more-details">
                        <a href="user-profile.html"><i class="ti-user"></i>Mi cuenta</a>
                        <a href="#!"><i class="ti-settings"></i>Configuraciones</a>
                        <a href="auth-normal-sign-in.html"><i class="ti-layout-sidebar-left"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="p-15 p-b-0">
        </div>

        <ul class="pcoded-item pcoded-left-item">

            <li class="active">
                <a href="../examinador/index.php" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>E</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Examinador</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

        </ul>
        <ul class="pcoded-item pcoded-left-item">
            <li>
                <a href="chart.html" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-layers"></i><b>PE</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Programar examen
                        <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
        <ul class="pcoded-item pcoded-left-item">
            <li>
                <a  class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-layers"></i><b>RP</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main" data-bs-toggle="modal" data-bs-target="#preguntaModal">Registrar preguntas</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <!-- Button trigger modal 
                 
                <button type="button" class="btn btn-primary" >
                    Launch demo modal
                </button>
                -->

            </li>
        </ul>
        <ul class="pcoded-item pcoded-left-item">
            <li>
                <a href="chart.html" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-layers"></i><b>RR</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Registrar respuesta</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
        <ul class="pcoded-item pcoded-left-item">
            <li>
                <a href="chart.html" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-layers"></i><b>R</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Repositorio</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>