<?php

// Suponiendo que ya tienes $estudiante_id y $examen_id
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_intentos FROM intentos_examen 
    WHERE estudiante_id = ? AND examen_id = ?");
$stmt->execute([$estudiante_id, $examen_id]);
$data = $stmt->fetch();

if ($data['total_intentos'] >= 1) {
    // Consultar fecha de próximo intento
    $stmt = $pdo->prepare("SELECT fecha_proximo_intento FROM examenes_estudiantes 
        WHERE estudiante_id = ? AND categoria_carne_id = ?");
    $stmt->execute([$estudiante_id, $categoria_carne_id]);
    $fila = $stmt->fetch();

    echo '<div class="alert alert-info">
        Ya has realizado un intento. Podrás volver a intentarlo a partir del 
        <strong>' . htmlspecialchars($fila['fecha_proximo_intento']) . '</strong>.
    </div>';
    exit; // Evita que acceda
}
?>