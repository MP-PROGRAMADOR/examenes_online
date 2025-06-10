<?php


session_start();
require_once '../includes/conexion.php';
require_once 'fpdf.php'; // Ruta correcta a FPDF

try {
    $examen_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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

    // Crear el PDF
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial','B',14);
            $this->Cell(0,10,'Ticket de Examen',0,1,'C');
            $this->Ln(5);
        }
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    $pdf = new PDF('P', 'mm', array(80,150));
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $pdf->Cell(0,10,"Nombre: " . $resultado['nombre'] . " " . $resultado['apellidos'], 0, 1);
    $pdf->Cell(0,10,"Email: " . $resultado['email'], 0, 1);
    $pdf->Cell(0,10,"Usuario: " . $resultado['usuario'], 0, 1);
    $pdf->Cell(0,10,"Categoria: " . $resultado['categoria'], 0, 1);
    $pdf->Cell(0,10,"Total Preguntas: " . $resultado['total_preguntas'], 0, 1);
    $pdf->Cell(0,10,"Fecha: " . $resultado['fecha_asignacion'], 0, 1);

    $pdf->Output('I', 'ticket_examen.pdf');
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Error al generar PDF: " . $e->getMessage();
    header("Location: ../secretaria/resultados.php");
    exit;
}


?>