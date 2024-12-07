<?php
include_once 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_cotizacion'], $_POST['estado'])) {
        $id_cotizacion = intval($_POST['id_cotizacion']);
        $estado = $_POST['estado'];
        $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : null;

        $database = new Database();
        $conexion = $database->getConnection();

        // Iniciar transacción
        $conexion->begin_transaction();

        try {
            // Actualizar estado de la cotización
            $sql = "UPDATE Cotizaciones SET estado = ? WHERE id_cotizacion = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('si', $estado, $id_cotizacion);
            
            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar el estado de la cotización');
            }
            $stmt->close();

            // Si es aceptada y hay producto, podrías hacer más operaciones aquí
            // Por ejemplo, marcar el producto como reservado, etc.

            // Confirmar transacción
            $conexion->commit();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conexion->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        $conexion->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>