<?php
session_start();

/* if (isset($_POST['motivo'])) {
    $_SESSION['examen_cancelado'] = [
      'motivo' => $_POST['motivo'],
      'fecha' => date('Y-m-d H:i:s')
    ];
    http_response_code(200);
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Motivo no recibido']);
}
 */

 
$_SESSION['examen_cancelado'] = [
  'motivo' => 'abandono',
  'fecha' => date('Y-m-d H:i:s')
];
header("Location: ../estudiante/aspirante.php");
exit;
