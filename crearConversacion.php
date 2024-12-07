<?php
include_once 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'];
    $id_vendedor = $_POST['id_vendedor'];
    $id_comprador = $_POST['id_comprador'];

    $database = new Database();
    $conexion = $database->getConnection();

    // Verificar si ya existe una conversación
    $query = "SELECT id_conversacion FROM Conversaciones 
              WHERE id_producto = ? AND id_vendedor = ? AND id_comprador = ? AND estado_conversacion = 'activa'";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iii", $id_producto, $id_vendedor, $id_comprador);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Insertar nueva conversación
        $insert = "INSERT INTO Conversaciones (id_producto, id_vendedor, id_comprador, estado_conversacion) 
                   VALUES (?, ?, ?, 'activa')";
        $insertStmt = $conexion->prepare($insert);
        $insertStmt->bind_param("iii", $id_producto, $id_vendedor, $id_comprador);
        $insertStmt->execute();
    }

    $stmt->close();
    $conexion->close();

    // Redirigir a Cotizacion.php
    header("Location: Cotizacion.php?id_producto=$id_producto&id_vendedor=$id_vendedor");
    exit;
}
?>
