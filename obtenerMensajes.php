<?php
include 'conexion.php';
session_start();  

if (isset($_GET['id_conversacion'])) {
    $id_conversacion = intval($_GET['id_conversacion']);
    $id_usuario = $_SESSION['id_usuario']; // Usuario actual
    $ultima_fecha = $_GET['ultima_fecha'] ?? null; // Fecha del último mensaje cargado (si existe)

    $query = "
        SELECT 
            m.contenido, 
            m.fecha_mensaje, 
            m.tipo,
            CASE WHEN m.id_remitente = ? THEN 'yo' ELSE 'otro' END AS remitente
        FROM Mensajes m
        WHERE m.id_conversacion = ?
    ";

    // Si se proporciona `ultima_fecha`, filtrar por mensajes más recientes
    if ($ultima_fecha) {
        $query .= " AND m.fecha_mensaje > ?";
    }

    $query .= " ORDER BY m.fecha_mensaje ASC";

    $conexion = (new Database())->getConnection();
    $stmt = $conexion->prepare($query);

    if ($ultima_fecha) {
        $stmt->bind_param("iis", $id_usuario, $id_conversacion, $ultima_fecha);
    } else {
        $stmt->bind_param("ii", $id_usuario, $id_conversacion);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $mensajes = [];
    while ($row = $result->fetch_assoc()) {
        $mensajes[] = $row;
    }

    echo json_encode($mensajes);
    $conexion->close();
}
?>