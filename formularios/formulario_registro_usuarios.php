<?php

require '../conexion/conexion.php';

$sqlcentro = "SELECT * FROM centro_procedencia";

$centro = $conn->query($sqlcentro);


?>



<!-- Modal -->
<div class="modal fade row justify-content-center" id="modalusuario" tabindex="-1" aria-labelledby="modalusuarioLabel" aria-hidden="true">
    <div class="modal-dialog col-md-8 grid-margin stretch-card">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalusuarioLabel">Informacion del Usuario </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">


                <form action="../php/registrar_usuarios.php" method="POST" enctype="multipart/form-data">

                    <div class="d-flex flex-row justify-content-center">

                        <div class="p-2 col-lg-5">

                            <label for="nombre" class="form-label">NOMBRE</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="NOMBRE" required>
                        </div>

                        <div class="p-2 col-lg-5">
                            <label for="tipo_usuario" class="form-label">TIPO DE USUARIO</label>
                            <select class="form-control" aria-label=".form-select-lg example" id="tipo_usuario" name="tipo_usuario" required>
                                <option selected value="">seleccione....</option>
                                <option value="admin">ADMINISTRADOR</option>
                                <option value="examinador">EXAMINADOR</option>
                                <option value="aspirante">ASPIRANTE</option>
                            </select>
                        </div>

                    </div>


                    <div class="d-flex flex-row justify-content-center">




                        <div class="p-2 col-lg-5">

                            <label for="fecha_registro" class="form-label">FECHA DE REGISTRO</label>
                            <input type="date" class="form-control" name="fecha_registro" id="fecha_registro" required>
                        </div>


                        <div class="p-2 col-lg-5">
                            <label for="centro" class="form-label">CENTRO DE PROCEDENCIA</label>

                            <select class="form-control" aria-label=".form-select-lg example" id="centro" name="centro" required>
                                <option selected value="">seleccione.....</option>

                                <?php

                                while ($row_consultas = $centro->fetch_assoc()) { ?>

                                    <option value=" <?php echo  $row_consultas['id_centro'];  ?>  "> <?php echo  $row_consultas['nombre']; ?> </option>

                                <?php }  ?>

                            </select>


                        </div>

                    </div>



                    <div class="d-flex flex-row justify-content-center">




                        <div class="p-2 col-lg-5">

                            <label for="dip" class="form-label"></label>
                            <input type="text" class="form-control" name="dip" id="dip" placeholder="D.I.P" required>
                        </div>


                        <div class="p-2 col-lg-5">

                            <label for="edad" class="form-label"></label>
                            <input type="NUMBER" class="form-control" name="edad" id="edad" placeholder="EDAD" required min="17">
                        </div>

                    </div>



                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">CERRAR</button>
                        <button type="submit" class="btn btn-primary btn-sm">GUARDAR CAMBIOS</button>
                    </div>

                </form>


            </div>

        </div>
    </div>
</div>