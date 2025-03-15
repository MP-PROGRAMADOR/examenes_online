









<!-- Modal -->
<div class="modal fade row justify-content-center" id="modalusuario" tabindex="-1" aria-labelledby="modalusuarioLabel" aria-hidden="true">
    <div class="modal-dialog col-md-8 grid-margin stretch-card">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalusuarioLabel">Informacion del Usuario </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="../php/guardar_usuario.php" method="post" enctype="multipart/form-data">
            <div class="modal-body">

            


                <div class="d-flex flex-row justify-content-center">

                    <div class="p-2 col-lg-5">

                        <label for="nombre_usuario" class="form-label">CODIGO DEL PERSONAL</label>
                        <input type="number" class="form-control" name="dip_personal" id="dip_personal" placeholder="ID - PERSONAL" required>
                    </div>

                    <div class="p-2 col-lg-5">

                        <label for="nombre_usuario" class="form-label">NOMBRE DE USUARIO</label>
                        <input type="txt" class="form-control" name="nombre_usuario" id="nombre_usuario" placeholder="NOMBRE DE USUARIO" required>
                    </div>


                </div>


                <div class="d-flex flex-row justify-content-center">




                    <div class="p-2 col-lg-5">

                        <label for="nombre" class="form-label">PASSWORD</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="password" required>
                    </div>


                    <div class="p-2 col-lg-5">
                        <label for="nombre" class="form-label">ROL</label>
                        <select class="form-control" aria-label=".form-select-lg example" id="rol" name="rol" required>
                            <option selected value="">seleccione.....</option>


                        </select>
                    </div>

                </div>


                
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>

            </form>
        </div>
    </div>
</div>











