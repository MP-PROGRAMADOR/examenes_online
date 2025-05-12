<?php include_once('includes/header.php') ;

$id = $estudiante['id']; 

require '../config/conexion.php'; 
$pdo = $pdo->getConexion();

// Obtener el id de la categoría del carne del estudiante
$stmtCategoria = $pdo->prepare("SELECT categoria_carne_id FROM estudiantes WHERE id = :id ORDER BY id DESC LIMIT 1");
$stmtCategoria->execute(['id' => $id]);
$categoria_carne = $stmtCategoria->fetch(PDO::FETCH_ASSOC); // Usamos fetch para obtener una sola fila

if ($categoria_carne) {
    $id_carne = $categoria_carne['categoria_carne_id'];

    // Obtener el examen asociado a la categoría del carne
    $stmtExamen = $pdo->prepare("SELECT * FROM examenes WHERE categoria_carne_id = :id ORDER BY id DESC LIMIT 1");
    $stmtExamen->execute(['id' => $id_carne]);
    $examen = $stmtExamen->fetch(PDO::FETCH_ASSOC); // Usamos fetch para obtener una sola fila
} 

?>
    <main class="container my-5 flex-grow-1 main-section">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="info-card">
                    <h2>Información Importante del Examen</h2>
                    <p class="lead">Antes de comenzar, por favor lee atentamente las políticas y condiciones del examen.</p>

                    <h3>Políticas del Examen</h3>
                    <ul>
                        <li><strong>Duración del Examen:</strong> Este examen tiene una duración máxima de <span id="exam-duration">60 minutos</span>. Una vez que inicies, el tiempo comenzará a correr y no se detendrá.</li>
                        <li><strong>Número de Preguntas:</strong> El examen consta de <span id="exam-questions">30 preguntas</span> de opción múltiple.</li>
                        <li><strong>Navegación:</strong> Puedes navegar libremente entre las preguntas. Revisa bien tus respuestas antes de finalizar.</li>
                        <li><strong>Finalización:</strong> Una vez que hayas respondido todas las preguntas o se agote el tiempo, podrás finalizar el examen y ver tu puntuación.</li>
                        <li><strong>Integridad Académica:</strong> Se espera que realices este examen de manera individual y sin ayuda externa. Cualquier intento de plagio o copia resultará en la descalificación.</li>
                        <li><strong>Conexión a Internet:</strong> Asegúrate de tener una conexión a internet estable durante todo el examen para evitar interrupciones.</li>
                    </ul>

                    <h3>Condiciones del Examen</h3>
                    <ul>
                         <li><strong>Puntuación:</strong> La puntuación se basará en el número de respuestas correctas. Cada pregunta tiene el mismo valor.</li>
                        <li><strong>Resultados:</strong> Los resultados del examen estarán disponibles inmediatamente después de la finalización.</li>
                        <li><strong>Revisión:</strong> En caso de dudas sobre alguna pregunta o resultado, puedes contactar a tu instructor a través de la plataforma.</li>
                        <li><strong>Intentos:</strong> Tendrás un máximo de <span id="exam-attempts">un intento</span> para realizar este examen.</li>
                        <li><strong>Confidencialidad:</strong> El contenido de este examen es confidencial y no debe ser compartido con otros estudiantes.</li>
                    </ul>

                    <div class="important-note">
                        <strong>Importante:</strong> Al hacer clic en "Comenzar Examen", confirmas que has leído y aceptas todas las políticas y condiciones mencionadas anteriormente. ¡Mucho éxito!
                    </div>

                    <a href="evaluacion.php?id=<?= $examen['id'] ?>" class="btn btn-start-exam btn-block mt-4">Comenzar Examen</a>
                  
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Autoescuela Online</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Puedes personalizar la duración, número de preguntas e intentos desde JavaScript si es dinámico
        document.getElementById('exam-duration').textContent = '45 minutos';
        document.getElementById('exam-questions').textContent = '25 preguntas';
        document.getElementById('exam-attempts').textContent = 'dos intentos';
    </script>
</body>
</html>