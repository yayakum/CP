<?php
include 'conexion.php';
session_start();  

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id_conversacion'], $data['contenido'])) {
    $id_conversacion = intval($data['id_conversacion']);
    $contenido = $data['contenido'];
    $id_remitente = $_SESSION['id_usuario']; // Usuario actual

    $query = "INSERT INTO Mensajes (id_conversacion, id_remitente, contenido) VALUES (?, ?, ?)";

    $conexion = (new Database())->getConnection();
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iis", $id_conversacion, $id_remitente, $contenido);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $conexion->close();
}
?>