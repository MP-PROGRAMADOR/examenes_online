 
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portal de Exámenes de Conducción | Guinea Ecuatorial</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f1f5f9;
      color: #1e293b;
      font-family: 'Segoe UI', sans-serif;
    }
    .hero {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: white;
      padding: 100px 20px;
      text-align: center;
    }
    .hero h1 {
      font-size: 2.8rem;
      margin-bottom: 10px;
    }
    .hero p {
      font-size: 1.2rem;
      color: #cbd5e1;
    }
    .section {
      padding: 60px 20px;
    }
    .section h2 {
      text-align: center;
      margin-bottom: 40px;
    }
    .feature-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      padding: 30px;
      height: 100%;
    }
    .feature-card h5 {
      margin-bottom: 10px;
    }
    footer {
      padding: 30px 0;
      text-align: center;
      font-size: 0.9rem;
      color: #64748b;
    }
  </style>
</head>
<body>

  <div class="hero">
    <h1>Examen Teórico Oficial para el Carné de Conducir</h1>
    <p>Sistema autorizado para la preparación y evaluación teórica en Guinea Ecuatorial</p>
  </div>

  <div class="container section">
    <h2>¿Qué ofrece nuestro sistema?</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card text-center">
          <h5>📚 Formación Teórica Oficial</h5>
          <p>Accede a un banco de preguntas oficiales adaptadas a la normativa vigente del país. Aprende sobre señales, normas y seguridad vial.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center">
          <h5>🎓 Preparación Profesional</h5>
          <p>Diseñado para centros de formación vial y usuarios particulares que desean entrenar en un entorno similar al examen real.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center">
          <h5>🛂 Certificación Reconocida</h5>
          <p>Los resultados obtenidos pueden ser revisados por instructores y autoridades autorizadas. Paso esencial para obtener el carné de conducir oficial.</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="info.php" class="btn btn-outline-secondary me-2">Más información</a>
      <a href="./modulos/index.php" class="btn btn-primary">Iniciar sesión</a>
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> República de Guinea Ecuatorial — Ministerio de Transporte | Todos los derechos reservados.
  </footer>

</body>
</html>
