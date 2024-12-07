<?php
include_once 'apiUsuarios.php';
session_start();

if ($_SESSION['id_rol'] != 2) {
    echo json_encode([
        'success' => false,
        'error' => 'No autorizado'
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id_conversacion = $data['id_conversacion'] ?? null;
$nuevo_estado = $data['estado'] ?? null;

if ($id_conversacion && $nuevo_estado) {
    $api = new ApiUsuarios();
    $resultado = $api->actualizarEstadoConversacion($id_conversacion, $nuevo_estado);
    
    echo json_encode([
        'success' => $resultado,
        'error' => $resultado ? null : 'Error al actualizar el estado'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Datos incompletos'
    ]);
}