<?php
session_start();
require 'conexion.php';

$database = new Database();
$conexion = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_lista = isset($_POST['id_lista']) ? intval($_POST['id_lista']) : 0;
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;

    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
        exit();
    }

    if ($id_lista > 0 && $id_producto > 0) {
        try {
            // Borrar el producto específico de la lista
            $query = "DELETE FROM Productos_en_lista WHERE id_lista = ? AND id_producto = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ii", $id_lista, $id_producto);

            if ($stmt->execute()) {
                // Devolver respuesta exitosa
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto eliminado exitosamente'
                ]);
            } else {
                throw new Exception("Error al eliminar el producto: " . $stmt->error);
            }
        } catch (Exception $e) {
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
            'message' => 'ID de lista o producto inválido'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de solicitud inválido'
    ]);
}
?>
