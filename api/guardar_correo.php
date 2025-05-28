<?php
// Conexión a la base de datos (ajusta con tu archivo de conexión)
require_once '../includes/conexion.php';
require_once 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



header('Content-Type: application/json');
// Validación del método

// Validación de solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false, 'message'  => 'Método no permitido']);
    exit;
}

// Sanitizar y validar datos
$estudiante_id = isset($_POST['estudiante_id']) ? (int) $_POST['estudiante_id'] : 0;
$tipo_correo   = isset($_POST['tipo_correo']) ? trim($_POST['tipo_correo']) : '';
$asunto        = isset($_POST['asunto']) ? trim($_POST['asunto']) : '';
$cuerpo        = isset($_POST['cuerpo']) ? trim($_POST['cuerpo']) : '';
$enviado_por   = 1; // Aquí puedes usar el ID del usuario autenticado

// Validaciones básicas
if (!$estudiante_id || !$tipo_correo || !$asunto || !$cuerpo) {
    echo json_encode(['success'=>false, 'message'  => 'Todos los campos son obligatorios']);
    exit;
}

try {
    

    // Obtener email del estudiante
    $stmt = $pdo->prepare("SELECT email, nombre FROM estudiantes WHERE id = ?");
    $stmt->execute([$estudiante_id]);
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante || empty($estudiante['email'])) {
        echo json_encode(['success'=>false, 'message'  => 'El estudiante no tiene un correo registrado']);
        exit;
    }

    $email_destino = $estudiante['email'];
    $nombre_destino = $estudiante['nombre'];

    // Enviar correo con PHPMailer
    $mail = new PHPMailer(true);

    // Configura tu servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.tuservidor.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tu_correo@tudominio.com';
    $mail->Password   = 'tu_contraseña';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remitente
    $mail->setFrom('tu_correo@tudominio.com', 'Sistema de Exámenes');
    $mail->addAddress($email_destino, $nombre_destino);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body    = nl2br($cuerpo);
    $mail->AltBody = strip_tags($cuerpo);

    $mail->send();

    // Registrar en la base de datos
    $insert = $pdo->prepare("
        INSERT INTO correos_enviados (estudiante_id, tipo_correo, asunto, cuerpo, enviado_por)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert->execute([$estudiante_id, $tipo_correo, $asunto, $cuerpo, $enviado_por]);

    echo json_encode(['success' => true, 'message'  => 'Correo enviado y registrado correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success'=>false, 'message'  => 'Error al enviar el correo: ' . $mail->ErrorInfo]);
} catch (PDOException $e) {
    echo json_encode(['success'=>false, 'message'  => 'Error de base de datos: ' . $e->getMessage()]);
}
?>
