<?php
session_start();
require 'conexion.php'; 

$database = new Database();
$conexion = $database->getConnection();

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de la lista
    $id_lista = isset($_POST['id_lista']) ? intval($_POST['id_lista']) : 0;

    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
        exit();
    }

    if ($id_lista > 0) {
        try {
            // Iniciar una transacción para garantizar consistencia
            $conexion->begin_transaction();

            // Primero borrar los registros de la tabla productos_en_lista
            $query1 = "DELETE FROM Productos_en_lista WHERE id_lista = ?";
            $stmt1 = $conexion->prepare($query1);
            $stmt1->bind_param("i", $id_lista);
            if (!$stmt1->execute()) {
                throw new Exception("Error al borrar productos de la lista: " . $stmt1->error);
            }

            // Luego borrar la lista
            $query2 = "DELETE FROM Listas WHERE id_lista = ?";
            $stmt2 = $conexion->prepare($query2);
            $stmt2->bind_param("i", $id_lista);
            if (!$stmt2->execute()) {
                throw new Exception("Error al borrar la lista: " . $stmt2->error);
            }

            // Confirmar la transacción
            $conexion->commit();

            // Devolver respuesta exitosa
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();

            // Devolver mensaje de error
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } finally {
            $database->cerrarConexion();
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ID de lista inválido'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de solicitud inválido'
    ]);
}
?>
