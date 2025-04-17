// Manejo del botón de eliminar con modal de confirmación
    $('.btn-eliminar-usuario').on('click', function () {
        const usuarioId = $(this).data('id');
        const usuarioNombre = $(this).data('nombre');
        $('#nombre-usuario-eliminar').text(usuarioNombre);
        $('#btn-confirmar-eliminar').data('id', usuarioId);
        $('#confirmarEliminarModal').modal('show');
    });

    $('#btn-confirmar-eliminar').on('click', function () {
        const usuarioId = $(this).data('id');
        // Redirigir o enviar una petición AJAX para eliminar el usuario
        window.location.href = 'eliminar_usuario.php?id=' + usuarioId; // Ejemplo de redirección
        $('#confirmarEliminarModal').modal('hide');
    });
