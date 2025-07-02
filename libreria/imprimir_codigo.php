<?php


session_start();
require_once '../includes/conexion.php';
require_once 'fpdf.php'; // Ruta correcta a FPDF
require('../fqr/qrlib.php'); // Ruta hacia qrlib.php

try {
    $examen_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if (!$examen_id) {
        throw new Exception("ID de examen no válido.");
    }

    // Consulta a la base de datos
    $sql = "SELECT 
                estudiantes.nombre,
                estudiantes.apellidos,
                estudiantes.email,
                estudiantes.usuario,
                categorias.nombre AS categoria,
                examenes.total_preguntas,
                examenes.calificacion,
                examenes.fecha_asignacion
            FROM estudiantes
            LEFT JOIN examenes ON examenes.estudiante_id = estudiantes.id
            LEFT JOIN categorias ON examenes.categoria_id = categorias.id
            WHERE examenes.id = :examen_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['examen_id' => $examen_id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        throw new Exception("No se encontró el examen con ID $examen_id.");
    }

    // Validar si GD está habilitado
    if (!function_exists('imagecreate')) {
        throw new Exception("La extensión GD no está habilitada. Actívala en php.ini");
    }



    // Crear QR temporal
    $qrData = "Nombre: {$resultado['nombre']} {$resultado['apellidos']}\n"
        . "Usuario: {$resultado['usuario']}\n"
        . "Email: {$resultado['email']}\n"
        . "Fecha: {$resultado['fecha_asignacion']}";
    $qrTempFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
    QRcode::png($qrData, $qrTempFile, QR_ECLEVEL_L, 3); // Nivel bajo, tamaño 3

    // Crear el PDF
    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->SetTextColor(33, 37, 41);
            $this->Cell(0, 10, 'TICKET DE EXAMEN', 0, 1, 'C');
            $this->SetDrawColor(100, 100, 100);
            $this->Line(5, 20, 75, 20);
            $this->Ln(5);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(150, 150, 150);
            $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF('P', 'mm', array(80, 150));
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetMargins(5, 5);
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0, 0, 0);

    $campos = [
        'Nombre' => $resultado['nombre'],
        'Apellidos' => $resultado['apellidos'],
        'Email' => $resultado['email'],
        'Usuario' => $resultado['usuario'],
        'Categoría' => $resultado['categoria'],
        'T.Preguntas' => $resultado['total_preguntas'],
        'F.Examen' => $resultado['fecha_asignacion'],
        'Calificacion' => $resultado['calificacion'],
    ];

    foreach ($campos as $label => $valor) {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 5, utf8_decode($label) . ':', 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 5, utf8_decode($valor), 0, 1);
        $pdf->Ln(1);
    }

    // Insertar QR en el lado inferior derecho
    $pdf->Image($qrTempFile, 25, $pdf->GetY() + 5, 30, 30); // x, y, ancho, alto
    $pdf->Ln(40);

    // Separador final
    $pdf->SetDrawColor(180, 180, 180);
    $pdf->Line(5, $pdf->GetY(), 75, $pdf->GetY());

    $pdf->Output('I', 'ticket_examen.pdf');
    unlink($qrTempFile); // Limpieza del archivo temporal
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = "Error al generar PDF: " . $e->getMessage();
    header("Location: ../secretaria/resultados.php");
    exit;
}
