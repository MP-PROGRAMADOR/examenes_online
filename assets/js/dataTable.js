// assets/js/examenes.js

// Confirmar eliminación
function confirmarEliminar(id, titulo) {
    document.getElementById('nombre-examen-eliminar').innerText = titulo;
    document.getElementById('enlace-eliminar').href = 'eliminar_examen.php?id=' + id;
    new bootstrap.Modal(document.getElementById('confirmarEliminarModal')).show();
}

// Inicialización de DataTable
$(document).ready(function () {
    const table = $('#container-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        lengthChange: false,
        info: true,
        lengthMenu: [5, 10, 15, 20, 25],
        language: {
            search: "",
            searchPlaceholder: "Buscar...",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Sin registros",
            zeroRecords: "No se encontraron resultados",
            paginate: {
                previous: "<i class='bi bi-chevron-left'></i>",
                next: "<i class='bi bi-chevron-right'></i>"
            }
        },
        order: [[0, 'asc']],
        dom: 'lrtip'
    });

    // Buscador personalizado
    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Cambiar cantidad de resultados por página
    $('#container-length').on('change', function () {
        table.page.len($(this).val()).draw();
    });
});
