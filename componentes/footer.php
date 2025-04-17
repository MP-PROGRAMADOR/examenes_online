



<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="../assets/js/dataTable.js"></script>
<script src="../assets/js/modal_alerta.js"></script>
<!-- Pasar variable PHP a JS (para modal de alerta) -->
<body data-alerta="<?= $alerta ? 'true' : 'false' ?>">

  <script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('show');
    });


     // Función para cerrar el modal automáticamente después de 5 segundos
     window.onload = function () {
            const alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
                keyboard: false,
                backdrop: 'static'
            });

            // Si hay un mensaje en la sesión, mostramos el modal y lo cerramos después de 5 segundos
            <?php if ($alerta): ?>
                alertModal.show();
                setTimeout(function () {
                    alertModal.hide();
                }, 5000); // Cierra el modal después de 5 segundos
            <?php endif; ?>
        }
  </script>
  

 

</body>
</html>
