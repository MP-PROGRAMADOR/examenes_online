


   
  </div>
  <!-- Bootstrap Bundle JS (necesario para componentes interactivos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- MDB JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
<script src="../js/alerta.js"></script>
 
  <script>
   const sidebar = document.getElementById("sidebar");
const hamburger = document.getElementById("hamburger");
const overlay = document.getElementById("overlay");
const main = document.getElementById("content"); // CORREGIDO

function toggleSidebar() {
  const isMobile = window.innerWidth <= 768;
  if (isMobile) {
    sidebar.classList.toggle("show");
    overlay.classList.toggle("d-none");
  } else {
    sidebar.classList.toggle("collapsed");
    main.classList.toggle("collapsed");
  }
}

hamburger.addEventListener("click", toggleSidebar);
overlay.addEventListener("click", () => {
  sidebar.classList.remove("show");
  overlay.classList.add("d-none");
});

  </script>
</body>
</html>
