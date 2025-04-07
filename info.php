<?php
// examenes_online/info.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Información del Sistema | Examen Teórico de Conducción</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8fafc;
      font-family: 'Segoe UI', sans-serif;
      color: #1e293b;
    }
    .header {
      background-color: #0f172a;
      color: white;
      padding: 60px 20px;
      text-align: center;
    }
    .header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    .header p {
      font-size: 1.1rem;
      color: #cbd5e1;
    }
    .section {
      padding: 60px 20px;
    }
    .section h2 {
      margin-bottom: 30px;
      text-align: center;
    }
    .card-info {
      background-color: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
      margin-bottom: 30px;
    }
    footer {
      text-align: center;
      padding: 30px 0;
      font-size: 0.9rem;
      color: #64748b;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Acerca del Sistema</h1>
    <p>Todo lo que necesitas saber sobre el proceso oficial para obtener tu carné de conducir teórico en Guinea Ecuatorial</p>
  </div>

  <div class="container section">
    <div class="card-info">
      <h4>📌 ¿Qué es este sistema?</h4>
      <p>Es una plataforma en línea diseñada exclusivamente para brindar acceso a exámenes teóricos oficiales con el fin de obtener el **carné de conducir** emitido por la **República de Guinea Ecuatorial**. El contenido cumple con los estándares y normativas nacionales de educación vial.</p>
    </div>

    <div class="card-info">
      <h4>📋 ¿Qué incluye?</h4>
      <ul>
        <li>Banco actualizado de preguntas oficiales</li>
        <li>Exámenes simulados bajo condiciones reales</li>
        <li>Evaluación automática de resultados</li>
        <li>Seguimiento y análisis del rendimiento</li>
        <li>Acceso especial para instructores y academias</li>
      </ul>
    </div>

    <div class="card-info">
      <h4>🛂 ¿Quién puede usarlo?</h4>
      <p>Este sistema está dirigido a:</p>
      <ul>
        <li>Ciudadanos que deseen obtener el carné de conducir</li>
        <li>Centros de formación vial y autoescuelas oficiales</li>
        <li>Funcionarios autorizados para verificar el avance del postulante</li>
      </ul>
    </div>

    <div class="card-info">
      <h4>✅ ¿Por qué confiar en esta plataforma?</h4>
      <p>Porque es parte del proceso avalado por el <strong>Ministerio de Transporte</strong> y cumple con los requisitos oficiales para presentarte al examen teórico.</p>
    </div>

    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-outline-secondary">Volver al inicio</a>
      <a href="login.php" class="btn btn-primary ms-2">Ingresar al sistema</a>
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> República de Guinea Ecuatorial — Sistema Oficial de Exámenes de Conducción
  </footer>

</body>
</html>
