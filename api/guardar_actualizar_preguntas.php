
<?php 

/* 
recibe los parametros para registro
    tipo_contenido -> text (puede ser texto y Ilustracion)
    texto -> text
    imagenes[] -> array (solo si es Ilustracion)
    tipo ->  selector [unica, multiple, vf]
    opciones[] -> text array
    es_correcta[] -> text array
    es_correcta_vf -> si se trata de opcion verdad valso
    categorias[] -> array si se aplica eleccion de categorias
    pregunta_id -> cuando se trate de edicion
    */



   
header('Content-Type: application/json');
require_once '../includes/conexion.php'; // Ajusta la ruta según tu estructura

try { 
  $pdo->beginTransaction();

  // === 1. DATOS DEL FORMULARIO ===
  $id = isset($_POST['pregunta_id']) ? intval($_POST['pregunta_id']) : null;
  $texto = $_POST['texto'] ?? '';
  $tipo = $_POST['tipo'] ?? '';
  $tipo_contenido = $_POST['tipo_contenido'] ?? 'texto';
  $activa = isset($_POST['activa']) ? intval($_POST['activa']) : 1;

  // Validar campos mínimos
  if (empty($texto) || empty($tipo)) {
    throw new Exception('Faltan campos obligatorios');
  }

  // === 2. INSERTAR o ACTUALIZAR PREGUNTA ===
  if ($id) {
    $stmt = $pdo->prepare("UPDATE preguntas SET texto = ?, tipo = ?, tipo_contenido = ?, activa = ? WHERE id = ?");
    $stmt->execute([$texto, $tipo, $tipo_contenido, $activa, $id]);
  } else {
    $stmt = $pdo->prepare("INSERT INTO preguntas (texto, tipo, tipo_contenido, activa) VALUES (?, ?, ?, ?)");
    $stmt->execute([$texto, $tipo, $tipo_contenido, $activa]);
    $id_registro = $pdo->lastInsertId();
    // === 3. GUARDAR OPCIONES ===
   
    if ($tipo === 'unica' || $tipo === 'multiple') { 
      foreach ($_POST['opciones'] ?? [] as $i => $opcion) {
        $texto_opcion = trim($opcion['texto'] ?? '');
        $es_correcta = isset($opcion['es_correcta']) ? 1 : 0;
        if ($texto_opcion !== '') {
          $pdo->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto, es_correcta) VALUES (?, ?, ?)")
              ->execute([$id_registro, $texto_opcion, $es_correcta]);
        }
      }
    } elseif ($tipo === 'vf') {
      $valor = $_POST['es_correcta_vf'] ?? '';
      if (in_array($valor, ['verdadero', 'falso'])) {
          $es_correcta = strtolower($valor) === 'verdadero' ? 1 : 0;
  
        $pdo->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto, es_correcta) VALUES (?, ?, ?)")
            ->execute([$id_registro, $texto,  $es_correcta ]);
      }
    }
  
    // === 4. GUARDAR CATEGORÍAS (si aplica) ===
    
    if ($_POST['asignar_categoria'] === 'si' && isset($_POST['categorias']) && is_array($_POST['categorias'])) {
      foreach ($_POST['categorias'] as $cat_id) {
        $pdo->prepare("INSERT INTO pregunta_categoria (pregunta_id, categoria_id) VALUES (?, ?)")
            ->execute([$id_registro, $cat_id]);
      }
    }
  
    // === 5. GUARDAR IMÁGENES (si hay) ===
    if (isset($_FILES['imagenes']) && is_array($_FILES['imagenes']['tmp_name'])) {
      $dir = "uploads/preguntas/";
      if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
      }
  
      foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmpPath) {
        if ($_FILES['imagenes']['error'][$i] === UPLOAD_ERR_OK) {
          $nombreArchivo = uniqid() . "_" . basename($_FILES['imagenes']['name'][$i]);
          $rutaFinal = $dir . $nombreArchivo;
  
          if (move_uploaded_file($tmpPath, $rutaFinal)) {
            $pdo->prepare("INSERT INTO imagenes_pregunta (pregunta_id, ruta_imagen) VALUES (?, ?)")
                ->execute([$id, $rutaFinal]);
          }
        }
      }
    }
  }


  $pdo->commit();

  echo json_encode(['status' => true, 'message' => $id ? 'Pregunta actualizada' : 'Pregunta registrada']);
} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}

