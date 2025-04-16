

 
<!-- Scripts Optimización -->
<script src="../../public/js/bootstrap.bundle.min.js"></script>
<script src="../../public/js/chart.js"></script>
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
      <!-- Cargar Scripts -->
 

</body>
</html>
