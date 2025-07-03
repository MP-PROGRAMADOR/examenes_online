<?php

require_once '../includes/conexion.php';
require_once 'fpdf.php'; // Asegúrate de que esta ruta es correcta para tu FPDF
// require('../fqr/qrlib.php'); // Descomenta si necesitas generar QR y ajusta la ruta si es necesario

class PDF_Reporte extends FPDF
{
    protected $examenData = []; // Para almacenar datos del examen para el encabezado/pie

    function setExamenData($data)
    {
        $this->examenData = $data;
    }

    // Cabecera de página
    function Header()
    {
        // Ruta de tu logo. Asegúrate de que esta ruta sea correcta.
        // Por ejemplo, si tu script está en libreria/imprimir_detalles_examen.php
        // y el logo está en libreria/images/escudo_guinea_ecuatorial.png
        $logoPath = 'img/escudo.png';
        $logoWidth = 25; // Ancho del logo en mm
        $logoHeight = 25; // Alto del logo en mm
        $logoX = 10; // Posición X del logo (margen izquierdo)
        $logoY = 10; // Posición Y del logo (margen superior)

        // Verificar si el archivo del logo existe para evitar errores
        if (file_exists($logoPath)) {
            $this->Image($logoPath, $logoX, $logoY, $logoWidth, $logoHeight);
        } else {
            // Opcional: Mostrar un mensaje de error si el logo no se encuentra
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(255, 0, 0);
            $this->Text($logoX, $logoY + ($logoHeight / 2), utf8_decode('Logo no encontrado.'));
            $this->SetTextColor(0, 0, 0); // Resetear color
        }

        // Título oficial del país
        $this->SetFont('Arial', 'B', 14); // Fuente más grande y negrita para el título oficial
        $this->SetTextColor(0, 0, 0); // Texto negro para el título oficial (más formal)
        // Calcula la posición para el texto al lado del logo
        $textX = $logoX + $logoWidth + 5; // 5mm de espacio entre el logo y el texto
        $this->SetXY($textX, $logoY + ($logoHeight / 4)); // Ajusta Y para centrar verticalmente con el logo
        $this->Cell(0, 10, utf8_decode('REPÚBLICA DE GUINEA ECUATORIAL'), 0, 1, 'L'); // 'L' para alineación izquierda

        // Reiniciar posición y estilo para el título del reporte
        $this->SetFillColor(30, 70, 100); // Azul oscuro
        $this->SetTextColor(255, 255, 255); // Texto blanco
        $this->SetFont('Arial', 'B', 16);
        // Posicionar el título del reporte debajo del logo y el texto oficial
        $this->SetY(max($logoY + $logoHeight + 5, $this->GetY() + 5)); // Asegura espacio suficiente
        $this->Cell(0, 12, utf8_decode('Reporte Detallado de Examen'), 0, 1, 'C', true); // Celda con fondo
        $this->SetTextColor(0, 0, 0); // Resetear color de texto a negro
        $this->Ln(8); // Espacio después del título principal

        if (!empty($this->examenData)) {
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 6, utf8_decode('Estudiante:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(100, 6, utf8_decode($this->examenData['nombre'] . ' ' . $this->examenData['apellidos']), 0, 0, 'L');
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(15, 6, utf8_decode('DNI:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 6, utf8_decode($this->examenData['dni']), 0, 1, 'L');

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(30, 6, utf8_decode('Categoría:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(100, 6, utf8_decode($this->examenData['categoria_nombre']), 0, 0, 'L');
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(15, 6, utf8_decode('Fecha:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 6, date('d/m/Y H:i', strtotime($this->examenData['fecha_asignacion'])), 0, 1, 'L');
            $this->Ln(5);
        }
    }

     

    // Pie de página
    function Footer()
    {
        // Posicionarse a 15 mm del final de la página
        $this->SetY(-15);

        // Estilo del texto del pie
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100, 100, 100); // Gris suave

        // Línea 1: Fecha y lugar
        $this->Cell(0, 5, utf8_decode('Malabo, a ' . date('d/m/Y H:i:s')), 0, 1, 'C');

        // Línea 2: Frase
        $this->Cell(0, 5, utf8_decode('POR UNA GUINEA MEJOR'), 0, 1, 'C');

        // Línea 3: Página actual (alineada a la derecha)
        $this->Cell(0, 5, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'R');
    }

    // Función para manejar texto multilinea con salto de línea automático
    function MultiCellBorders($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        $this->MultiCell($w, $h, $txt, $border, $align, $fill);
        // Dentro de la clase, $this->lMargin es accesible.
        $this->x = $this->lMargin;
    }

    /**
     * Helper function to position cursor for status/score at the right.
     * This method can access protected properties like $this->rMargin.
     */
    function SetXForRightStatus()
    {
        // Acceso permitido a $this->rMargin dentro de un método de la clase
        $this->SetX($this->GetPageWidth() - $this->rMargin - 60);
    }

    /**
     * Helper function to position cursor at the left margin.
     * This method can access protected properties like $this->lMargin.
     */
    function SetXForLeftMargin()
    {
        // Acceso permitido a $this->lMargin dentro de un método de la clase
        $this->SetX($this->lMargin);
    }
 }
// Validar y sanear el ID del examen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo "ID de examen no válido.";
    exit;
}

$id_examen = intval($_GET['id']);

try {
    // 1. Cargar datos generales del examen
    $sqlExamen = "
    SELECT
        e.id AS examen_id,
        est.nombre,
        est.apellidos,
        est.dni,
        c.nombre AS categoria_nombre,
        e.total_preguntas,
        e.calificacion,
        e.fecha_asignacion
    FROM examenes e
    JOIN estudiantes est ON est.id = e.estudiante_id
    JOIN categorias c ON c.id = e.categoria_id -- Corregida esta JOIN, debe ser e.categoria_id
    WHERE e.id = :id_examen";
    $stmtExamen = $pdo->prepare($sqlExamen);
    $stmtExamen->execute([':id_examen' => $id_examen]);
    $examen = $stmtExamen->fetch(PDO::FETCH_ASSOC);

    if (!$examen) {
        http_response_code(404); // Not Found
        echo "Examen no encontrado.";
        exit;
    }

    // 2. Obtener todas las preguntas del examen, sus opciones y las respuestas del estudiante
    $sqlPreguntasDetalle = "
    SELECT
        ep.id AS examen_pregunta_id,
        p.id AS pregunta_id,
        p.texto AS texto_pregunta,
        p.tipo,
        p.tipo_contenido,
        ip.ruta_imagen AS imagen_ruta,
        op.id AS opcion_id,
        op.texto AS opcion_texto,
        op.es_correcta,
        (SELECT GROUP_CONCAT(re_sub.opcion_id ORDER BY re_sub.opcion_id)
         FROM respuestas_estudiante re_sub
         WHERE re_sub.examen_pregunta_id = ep.id) AS respuestas_estudiante_ids_str_total
    FROM examen_preguntas ep
    JOIN preguntas p ON p.id = ep.pregunta_id
    LEFT JOIN imagenes_pregunta ip ON p.id = ip.pregunta_id
    JOIN opciones_pregunta op ON op.pregunta_id = p.id
    WHERE ep.examen_id = :id_examen
    ORDER BY ep.id, op.id;
    ";

    $stmtPreguntasDetalle = $pdo->prepare($sqlPreguntasDetalle);
    $stmtPreguntasDetalle->execute([':id_examen' => $id_examen]);
    $preguntasRaw = $stmtPreguntasDetalle->fetchAll(PDO::FETCH_ASSOC);

    $preguntasDetalladas = [];
    $totalAciertos = 0;
    $totalFallos = 0;
    $preguntasRespondidas = 0;

    foreach ($preguntasRaw as $row) {
        $examenPreguntaId = $row['examen_pregunta_id'];
        $preguntaId = $row['pregunta_id'];

        if (!isset($preguntasDetalladas[$examenPreguntaId])) {
            $preguntasDetalladas[$examenPreguntaId] = [
                'pregunta_id' => $preguntaId,
                'texto_pregunta' => utf8_decode($row['texto_pregunta']),
                'tipo' => $row['tipo'],
                'tipo_contenido' => $row['tipo_contenido'],
                'imagen_ruta' => $row['imagen_ruta'],
                'opciones' => [],
                'respuestas_estudiante_ids' => [],
                'correctas_pregunta_ids' => [],
                'acierto_pregunta_completa' => false,
                'puntuacion_pregunta' => 0
            ];
            if (!empty($row['respuestas_estudiante_ids_str_total'])) {
                $ids_seleccionadas = array_map('intval', explode(',', $row['respuestas_estudiante_ids_str_total']));
                $preguntasDetalladas[$examenPreguntaId]['respuestas_estudiante_ids'] = array_unique($ids_seleccionadas);
            }
        }

        $opcion_id = intval($row['opcion_id']);
        $es_correcta_opcion = (bool) $row['es_correcta'];

        $preguntasDetalladas[$examenPreguntaId]['opciones'][$opcion_id] = [
            'opcion_id' => $opcion_id,
            'texto' => utf8_decode($row['opcion_texto']),
            'es_correcta' => $es_correcta_opcion
        ];

        if ($es_correcta_opcion) {
            $preguntasDetalladas[$examenPreguntaId]['correctas_pregunta_ids'][] = $opcion_id;
        }
    }

    foreach ($preguntasDetalladas as $examenPreguntaId => &$preg) {
        $correctasPregunta = array_unique($preg['correctas_pregunta_ids']);
        $seleccionadasEstudiante = array_unique($preg['respuestas_estudiante_ids']);

        sort($correctasPregunta);
        sort($seleccionadasEstudiante);

        $numCorrectasSeleccionadas = 0;
        $ningunaIncorrectaSeleccionada = true;
        $preguntaFueRespondida = !empty($seleccionadasEstudiante);

        if ($preguntaFueRespondida) {
            $preguntasRespondidas++;

            foreach ($seleccionadasEstudiante as $selId) {
                if (in_array($selId, $correctasPregunta)) {
                    $numCorrectasSeleccionadas++;
                } else {
                    $ningunaIncorrectaSeleccionada = false;
                }
            }

            if ($preg['tipo'] === 'multiple') {
                if ($ningunaIncorrectaSeleccionada) {
                    if (count($correctasPregunta) > 0) {
                        $puntuacionParcial = ($numCorrectasSeleccionadas / count($correctasPregunta));
                    } else {
                        $puntuacionParcial = 0;
                    }
                } else {
                    $puntuacionParcial = 0;
                }
            } else { // 'unica' o 'vf'
                if (
                    count($seleccionadasEstudiante) === count($correctasPregunta) &&
                    count(array_diff($seleccionadasEstudiante, $correctasPregunta)) === 0
                ) {
                    $puntuacionParcial = 1;
                } else {
                    $puntuacionParcial = 0;
                }
            }

            $preg['puntuacion_pregunta'] = round($puntuacionParcial, 2);
            $preg['acierto_pregunta_completa'] = ($preg['puntuacion_pregunta'] == 1);

            if ($preg['acierto_pregunta_completa']) {
                $totalAciertos++;
            } else {
                $totalFallos++;
            }
        } else {
            $preg['acierto_pregunta_completa'] = false;
            $preg['puntuacion_pregunta'] = 0;
        }
    }
    unset($preg);

    // Instanciar FPDF
    $pdf = new PDF_Reporte();
    $pdf->AliasNbPages();
    $pdf->setExamenData([
        'nombre' => utf8_decode($examen['nombre']),
        'apellidos' => utf8_decode($examen['apellidos']),
        'dni' => utf8_decode($examen['dni']),
        'categoria_nombre' => utf8_decode($examen['categoria_nombre']),
        'fecha_asignacion' => $examen['fecha_asignacion']
    ]);
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 15);

    // --- Resumen del Examen ---
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(220, 230, 240); // Azul muy claro para secciones
    $pdf->SetTextColor(30, 70, 100); // Azul oscuro para títulos de sección
    $pdf->Cell(0, 8, utf8_decode('RESUMEN DEL EXAMEN'), 0, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0); // Resetear a negro
    $pdf->Ln(2);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetFillColor(245, 245, 245); // Gris muy claro para celdas de tabla

    // Encabezados de la tabla de resumen
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(230, 230, 230); // Gris un poco más oscuro
    $pdf->Cell(45, 7, utf8_decode('Métrica'), 1, 0, 'L', true);
    $pdf->Cell(25, 7, utf8_decode('Valor'), 1, 0, 'C', true);
    $pdf->Cell(45, 7, utf8_decode('Métrica'), 1, 0, 'L', true);
    $pdf->Cell(25, 7, utf8_decode('Valor'), 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 9);
    $pdf->SetFillColor(255, 255, 255); // Blanco para el contenido de la tabla

    $pdf->Cell(45, 6, utf8_decode('Total Preguntas:'), 'LR', 0, 'L');
    $pdf->Cell(25, 6, $examen['total_preguntas'], 'R', 0, 'C');
    $pdf->Cell(45, 6, utf8_decode('Preguntas Respondidas:'), 'LR', 0, 'L');
    $pdf->Cell(25, 6, $preguntasRespondidas, 'R', 1, 'C');

    $pdf->Cell(45, 6, utf8_decode('Aciertos Totales:'), 'LR', 0, 'L');
    $pdf->Cell(25, 6, $totalAciertos, 'R', 0, 'C');
    $pdf->Cell(45, 6, utf8_decode('Fallos Totales:'), 'LR', 0, 'L');
    $pdf->Cell(25, 6, $totalFallos, 'R', 1, 'C');

    $pdf->Cell(45, 6, utf8_decode('Calificación Final:'), 'LBR', 0, 'L');
    $pdf->Cell(25, 6, round($examen['calificacion']) . '%', 'BR', 0, 'C');
    $pdf->Cell(45, 6, utf8_decode('Estado:'), 'LBR', 0, 'L');

    // Estado del examen con colores discretos
    $pdf->SetFont('Arial', 'B', 9);
    if ($examen['calificacion'] >= 80) {
        $pdf->SetTextColor(34, 139, 34); // Verde bosque
        $pdf->Cell(25, 6, 'APROBADO', 'BR', 1, 'C');
    } else {
        $pdf->SetTextColor(178, 34, 34); // Rojo ladrillo
        $pdf->Cell(25, 6, 'REPROBADO', 'BR', 1, 'C');
    }
    $pdf->SetTextColor(0, 0, 0); // Resetear color de texto
    $pdf->Ln(10);

    // --- Detalle de Preguntas ---
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(220, 230, 240); // Azul muy claro para secciones
    $pdf->SetTextColor(30, 70, 100); // Azul oscuro para títulos de sección
    $pdf->Cell(0, 8, utf8_decode('DETALLE DE PREGUNTAS'), 0, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0); // Resetear a negro
    $pdf->Ln(5);

    $preguntaNum = 0;
    foreach ($preguntasDetalladas as $examenPreguntaId => $preg) {
        $preguntaNum++;

        // Encabezado de la pregunta con texto y acierto/fallo
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(240, 248, 255); // Azul pálido para el fondo de la pregunta
        $pdf->Cell(0, 7, utf8_decode('Pregunta ' . $preguntaNum . ':'), 'LT', 0, 'L', true); // Parte izquierda del título

        // Mostrar estado y puntuación a la derecha
        $xBeforeStatus = $pdf->GetX(); // Guarda la posición actual de X
        $pdf->Cell(0, 7, '', 'RT', 1, 'R', true); // Cierra la celda y salta de línea

        $pdf->SetY($pdf->GetY() - 7); // Mueve Y hacia arriba para escribir dentro de la misma celda visualmente
        // Llamada al método de la clase para acceder a rMargin
        $pdf->SetXForRightStatus();
        $pdf->SetFont('Arial', 'B', 9);
        $statusText = '';
        $textColorStatus = [0, 0, 0];

        if ($preg['acierto_pregunta_completa']) {
            $statusText = utf8_decode('Correcta ');
            $textColorStatus = [34, 139, 34]; // Verde bosque
        } else {
            $statusText = utf8_decode('Incorrecta ');
            $textColorStatus = [178, 34, 34]; // Rojo ladrillo
        }
        $pdf->SetTextColor($textColorStatus[0], $textColorStatus[1], $textColorStatus[2]);
        $pdf->Cell(30, 7, $statusText, 0, 0, 'R');

        $pdf->SetTextColor(0, 0, 0); // Resetear a negro
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(30, 7, utf8_decode('Puntos: ' . sprintf("%.2f", $preg['puntuacion_pregunta'])), 0, 1, 'R');

        // Texto de la pregunta (cuerpo)
        $pdf->SetFont('Arial', '', 10);
        // Llamada al método de la clase para acceder a lMargin
        $pdf->SetXForLeftMargin();
        $pdf->MultiCellBorders(0, 6, $preg['texto_pregunta'], 'LR', 'L', true); // Con borde lateral

        // Imagen si existe
        if ($preg['tipo_contenido'] === 'ilustracion' && !empty($preg['imagen_ruta'])) {
            $imagePath = '../api/' . $preg['imagen_ruta']; // Ajusta esto según tu estructura de carpetas
            if (file_exists($imagePath)) {
                try {
                    // Calcula la posición X para centrar la imagen
                    $imageWidth = 20; // Ancho deseado de la imagen
                    $xImage = ($pdf->GetPageWidth() - $imageWidth) / 2;
                    $pdf->Image($imagePath, $xImage, null, $imageWidth);
                    $pdf->Ln(5);
                } catch (Exception $e) {
                    $pdf->SetFont('Arial', 'I', 8);
                    $pdf->SetTextColor(150, 50, 50); // Un rojo suave para errores
                    $pdf->Cell(0, 5, utf8_decode('Error al cargar imagen: ' . $e->getMessage()), 0, 1, 'C');
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Ln(2);
                }
            } else {
                $pdf->SetFont('Arial', 'I', 8);
                $pdf->SetTextColor(150, 150, 0);
                $pdf->Cell(0, 5, utf8_decode('Imagen no encontrada: ' . $preg['imagen_ruta']), 0, 1, 'C');
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Ln(2);
            }
        }
        $pdf->SetFillColor(255, 255, 255); // Resetear fondo

        // Opciones de la pregunta
        $pdf->SetFont('Arial', '', 9);
        $opcionLetra = 'a'; // Para las letras de las opciones

        foreach ($preg['opciones'] as $opcion) {
            // Estado inicial por defecto
            $prefijo = strtoupper($opcionLetra) . ') '; // Ej: A)
            $colorTexto = [0, 0, 0]; // Negro
            $fondo = [255, 255, 255]; // Blanco

            $esSeleccionada = in_array($opcion['opcion_id'], $preg['respuestas_estudiante_ids']);
            $esCorrectaOpcion = $opcion['es_correcta'];

            // Reglas de estilos según estado
            if ($esCorrectaOpcion) {
                $colorTexto = [23, 114, 69];         // Verde oscuro
                $fondo = [212, 237, 218];             // Verde claro

                if ($esSeleccionada) {
                    $prefijo = 'OK ' . strtoupper($opcionLetra) . ') '; // Reemplaza '✓' con "OK"
                    $fondo = [196, 226, 203];         // Verde más oscuro
                } else {
                    $prefijo = '-> ' . strtoupper($opcionLetra) . ') '; // Reemplaza '→' con "->"
                    $colorTexto = [130, 80, 0];       // Naranja
                    $fondo = [255, 243, 205];         // Amarillo claro
                }
            } elseif ($esSeleccionada) {
                $prefijo = 'X ' . strtoupper($opcionLetra) . ') '; // Reemplaza 'X' con "X" (un carácter básico)
                $colorTexto = [114, 33, 46];          // Rojo
                $fondo = [248, 215, 218];             // Rojo claro
            }

            // Aplicar estilo
            $pdf->SetFillColor($fondo[0], $fondo[1], $fondo[2]);
            $pdf->SetTextColor($colorTexto[0], $colorTexto[1], $colorTexto[2]);

            // Celda de prefijo (letra con texto)
            $pdf->Cell(15, 6, utf8_decode($prefijo), 'L', 0, 'L', true); // utf8_decode para el prefijo

            // Texto de la opción (usa MultiCellBorders si existe, si no, Cell)
            if (method_exists($pdf, 'MultiCellBorders')) {
                $pdf->MultiCellBorders(0, 6, $opcion['texto'], 'R', 'L', true);
            } else {
                $pdf->Cell(0, 6, $opcion['texto'], 'R', 1, 'L', true);
            }

            // Siguiente letra
            $opcionLetra++;

            // Reset estilos
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Ln(1); // Espacio entre opciones
        }

        $pdf->Cell(0, 0, '', 'T', 1); // Cierra el bloque de opciones con un borde inferior
        $pdf->Ln(5); // Espacio después de cada pregunta
    }

    // Output the PDF
    $filename = 'reporte_examen_' . $examen['examen_id'] . '_' . str_replace(' ', '_', utf8_decode($examen['nombre'])) . '.pdf';
    $pdf->Output('I', $filename);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error de base de datos en reporte de examen: " . $e->getMessage());
    echo "Error del servidor al generar el reporte: " . $e->getMessage();
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error al generar el reporte PDF con FPDF: " . $e->getMessage());
    echo "Error inesperado al generar el reporte: " . $e->getMessage();
}

?>