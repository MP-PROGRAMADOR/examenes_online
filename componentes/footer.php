<!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="assets/images/browser/chrome.png" alt="Chrome">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="assets/images/browser/firefox.png" alt="Firefox">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="assets/images/browser/opera.png" alt="Opera">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="assets/images/browser/safari.png" alt="Safari">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="assets/images/browser/ie.png" alt="">
                        <div>IE (9 & above)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
    <![endif]-->
    <!-- Warning Section Ends -->




<!-- datatables -->


<script src="../assets/js/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>



<script>
    $('#example').DataTable();
</script>

<!-- CODIGO PARA VISUALIZAR MODAL DE REGISTRO DE PREGUNTAS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
     const tipoPregunta = document.getElementById('tipoPregunta');
    const opcionesDiv = document.getElementById('opcionesDiv');
    const labelVerdaderoFalso = document.getElementById('labelVerdaderoFalso');
    const respuestaVerdaderoFalso = document.getElementById('respuestaVerdaderoFalso');
    const labelRespuestaCorta = document.getElementById('labelRespuestaCorta');
    const respuestaCorta = document.getElementById('respuestaCorta');
    const labelRespuestaEnsayo = document.getElementById('labelRespuestaEnsayo');
    const respuestaEnsayo = document.getElementById('respuestaEnsayo');
    const labelUrlGrafico = document.getElementById('labelUrlGrafico');
    const urlGrafico = document.getElementById('urlGrafico');

    tipoPregunta.addEventListener('change', function() {
    if (this.value === 'opcionMultiple') {
    opcionesDiv.style.display = 'block';
    labelVerdaderoFalso.style.display = 'none';
    respuestaVerdaderoFalso.style.display = 'none';
    labelRespuestaCorta.style.display = 'none';
    respuestaCorta.style.display = 'none';
    labelRespuestaEnsayo.style.display = 'none';
    respuestaEnsayo.style.display = 'none';
    labelUrlGrafico.style.display = 'none';
    urlGrafico.style.display = 'none';
    } else if (this.value === 'verdaderoFalso') {
    opcionesDiv.style.display = 'none';
    labelVerdaderoFalso.style.display = 'block';
    respuestaVerdaderoFalso.style.display = 'block';
    labelRespuestaCorta.style.display = 'none';
    respuestaCorta.style.display = 'none';
    labelRespuestaEnsayo.style.display = 'none';
    respuestaEnsayo.style.display = 'none';
    labelUrlGrafico.style.display = 'none';
    urlGrafico.style.display = 'none';
    } else if (this.value === 'respuestaCorta') {
    opcionesDiv.style.display = 'none';
    labelVerdaderoFalso.style.display = 'none';
    respuestaVerdaderoFalso.style.display = 'none';
    labelRespuestaCorta.style.display = 'block';
    respuestaCorta.style.display = 'block';
    labelRespuestaEnsayo.style.display = 'none';
    respuestaEnsayo.style.display = 'none';
    labelUrlGrafico.style.display = 'none';
    urlGrafico.style.display = 'none';
    } else if (this.value === 'ensayo') {
    opcionesDiv.style.display = 'none';
    labelVerdaderoFalso.style.display = 'none';
    respuestaVerdaderoFalso.style.display = 'none';
    labelRespuestaCorta.style.display = 'none';
    respuestaCorta.style.display = 'none';
    labelRespuestaEnsayo.style.display = 'block';
    respuestaEnsayo.style.display = 'block';
    labelUrlGrafico.style.display = 'none';
    urlGrafico.style.display = 'none';
    } else if (this.value === 'grafico') {
    opcionesDiv.style.display = 'none';
    labelVerdaderoFalso.style.display = 'none';
    respuestaVerdaderoFalso.style.display = 'none';
    labelRespuestaCorta.style.display = 'none';
    respuestaCorta.style.display = 'none';
    labelRespuestaEnsayo.style.display = 'none';
    respuestaEnsayo.style.display = 'none';
    labelUrlGrafico.style.display = 'block';
    urlGrafico.style.display = 'block';
    }
    });
   </script>
    <!--FIN CODIGO PARA VISUALIZAR MODAL DE REGISTRO DE PREGUNTAS-->


    <!-- CODIGO PARA VISUALIZAR MODAL DE REGISTRO DE PREGUNTAS opcion multiple-->

    <script  >
        document.addEventListener('DOMContentLoaded', function() {
    const opcionesLista = document.getElementById('opciones');
    const agregarOpcionBtn = document.getElementById('agregarOpcion');
    const preguntaForm = document.getElementById('preguntaForm');

    agregarOpcionBtn.addEventListener('click', function() {
        const nuevaOpcion = document.createElement('li');
        nuevaOpcion.innerHTML = '<input type="text" class="form-control" name="opcion[]">';
        opcionesLista.appendChild(nuevaOpcion);
    });

    preguntaForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const pregunta = document.getElementById('pregunta').value;
        const opciones = Array.from(document.querySelectorAll('input[name="opcion[]"]')).map(input => input.value);
        const respuestaCorrecta = document.getElementById('respuestaCorrecta').value;

        // Aquí puedes guardar la pregunta (por ejemplo, en un arreglo o enviarla a un servidor)
        const nuevaPregunta = {
            pregunta: pregunta,
            opciones: opciones,
            respuestaCorrecta: respuestaCorrecta
        };
        console.log(nuevaPregunta);

        $('#preguntaModal').modal('hide'); // Cierra el modal después de guardar
    });
});
    </script>
    <!--FIN CODIGO PARA VISUALIZAR MODAL DE REGISTRO DE PREGUNTAS opcion multiple-->


    
    <!-- Required Jquery -->
    <script type="text/javascript" src="../assets/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/js/jquery-ui/jquery-ui.min.js "></script>
    <script type="text/javascript" src="../assets/js/popper.js/popper.min.js"></script>
    <script type="text/javascript" src="../assets/js/bootstrap/js/bootstrap.min.js "></script>
    <script type="text/javascript" src="../assets/pages/widget/excanvas.js "></script>
    <!-- waves js -->
    <script src="../assets/pages/waves/js/waves.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="../assets/js/jquery-slimscroll/jquery.slimscroll.js "></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="../assets/js/modernizr/modernizr.js "></script>
    <!-- slimscroll js -->
    <script type="text/javascript" src="../assets/js/SmoothScroll.js"></script>
    <script src="../assets/js/jquery.mCustomScrollbar.concat.min.js "></script>
    <!-- Chart js -->
    <script type="text/javascript" src="assets/js/chart.js/Chart.js"></script>
    <!-- amchart js -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="../assets/pages/widget/amchart/gauge.js"></script>
    <script src="../assets/pages/widget/amchart/serial.js"></script>
    <script src="../assets/pages/widget/amchart/light.js"></script>
    <script src="../assets/pages/widget/amchart/pie.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <!-- menu js -->
    <script src="../assets/js/pcoded.min.js"></script>
    <script src="../assets/js/vertical-layout.min.js "></script>
    <!-- custom js -->
    <script type="text/javascript" src="../assets/pages/dashboard/custom-dashboard.js"></script>
    <script type="text/javascript" src="../assets/js/script.js "></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>