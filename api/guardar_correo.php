<?php
// Establece el tipo de respuesta como JSON
header('Content-Type: application/json');

// Requiere conexión a la base de datos
require_once('../includes/conexion.php');

// Requiere PHPMailer (versión estática)
require '../includes/phpmailer/PHPMailer.php';
require '../includes/phpmailer/SMTP.php';
require '../includes/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Función para sanitizar
function limpiar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
}

// Obtener y limpiar datos
$estudiante_id = isset($_POST['estudiante_id']) ? (int) $_POST['estudiante_id'] : 0;
$tipo_correo = isset($_POST['tipo_correo']) ? limpiar($_POST['tipo_correo']) : '';
$asunto = isset($_POST['asunto']) ? limpiar($_POST['asunto']) : '';
$cuerpo = isset($_POST['cuerpo']) ? limpiar($_POST['cuerpo']) : '';
$enviado_por = isset($_POST['enviado_por']) ? (int) $_POST['enviado_por'] : 0;

// Validación de campos
if ($estudiante_id <= 0 || empty($tipo_correo) || empty($asunto) || empty($cuerpo) || $enviado_por <= 0) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}
if (strlen($asunto) > 255) {
    echo json_encode(['success' => false, 'message' => 'El asunto no puede superar los 255 caracteres.']);
    exit;
}
$tipos_validos = ['registro', 'invitacion_examen', 'resultado', 'recordatorio'];
if (!in_array($tipo_correo, $tipos_validos)) {
    echo json_encode(['success' => false, 'message' => 'El tipo de correo es inválido.']);
    exit;
}

// Obtener correo del estudiante
try {
    $stmt = $pdo->prepare("SELECT email FROM estudiantes WHERE id = :id");
    $stmt->bindParam(':id', $estudiante_id, PDO::PARAM_INT);
    $stmt->execute();
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
        echo json_encode(['success' => false, 'message' => 'Estudiante no encontrado.']);
        exit;
    }

   $correo_destino = $estudiante['email'];

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al buscar estudiante: ' . $e->getMessage()]);
    exit;
}

// Enviar correo con PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración SMTP para Gmail
    $mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'sirtopola@gmail.com'; // tu Gmail
$mail->Password   = 'sirTopolaBT*73'; // NO tu contraseña real
$mail->SMTPSecure = 'tls';
$mail->Port       = 587;
$mail->CharSet    = 'UTF-8';

    
    // Configurar remitente y destinatario
    $mail->setFrom('sirtopola@gmail.com', 'Sistema de Exámenes'); // Reemplazar nombre si deseas
    $mail->addAddress($correo_destino);  // Estudiante
    $mail->CharSet = 'UTF-8';

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body    = nl2br($cuerpo); // permite saltos de línea

    // Enviar correo
    $mail->send();

    // Guardar en base de datos
    $sql = "INSERT INTO correos_enviados (estudiante_id, tipo_correo, asunto, cuerpo, enviado_por, enviado_en)
            VALUES (:estudiante_id, :tipo_correo, :asunto, :cuerpo, :enviado_por, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
    $stmt->bindParam(':tipo_correo', $tipo_correo, PDO::PARAM_STR);
    $stmt->bindParam(':asunto', $asunto, PDO::PARAM_STR);
    $stmt->bindParam(':cuerpo', $cuerpo, PDO::PARAM_STR);
    $stmt->bindParam(':enviado_por', $enviado_por, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Correo enviado y registrado exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => '⚠️ Correo enviado pero no se guardó en la base de datos.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => '❌ Error al enviar el correo: ' . $mail->ErrorInfo]);
}
?>
