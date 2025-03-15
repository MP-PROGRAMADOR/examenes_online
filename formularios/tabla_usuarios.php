<!-- End Navbar -->
<?php




?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">

                    
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Añadir Usuarios
                    </button>



                </div>




                <!-- alerta -->

                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'insertado') {
                ?>

                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong> Hola!</strong> su registro ha tenido Exito.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php } ?>
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'actualizado') {
                ?>

                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong> Hola!</strong> su registro ha sido actualizado.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php } ?>




                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'igual') {
                ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong> Hola!</strong> el DIP ya corresponde a un paciente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                <?php } ?>










                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="example" class="table table-striped p-4" style="width:100%">
                            <thead>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>CODIGO</th>
                                <th>TIPO DE USUARIO</th>
                                <th>FECHA DE REGISTRO</th>
                                <th>CENTRO DE PROCEDENCIA</th>
                                <th>DIP</th>
                                <th>EDAD</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>


                                <tr>
                                    <th>0</th>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td><a href="" class="btn btn-primary btn-sm">Editar</a></td>
                                </tr>



                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>CODIGO</th>
                                    <th>TIPO DE USUARIO</th>
                                    <th>FECHA DE REGISTRO</th>
                                    <th>CENTRO DE PROCEDENCIA</th>
                                    <th>DIP</th>
                                    <th>EDAD</th>
                                    <th>Acciones</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#example').DataTable();
    </script>