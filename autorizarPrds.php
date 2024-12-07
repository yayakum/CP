<?php
session_start();
require 'conexion.php'; 

$database = new Database();
$conexion = $database->getConnection();

if (isset($_POST['producto_ids'])) {
    // Recibe y decodifica los IDs de los productos
    $producto_ids = json_decode($_POST['producto_ids'], true);

    if (!empty($producto_ids)) {
        // Convertir los IDs en una cadena separada por comas para la consulta SQL
        $ids = implode(',', array_map('intval', $producto_ids));

        // Consulta para actualizar los productos
        $query = "UPDATE Productos SET autorizado = 1 WHERE id_producto IN ($ids)";
        $stmt = $conexion->prepare($query);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            // Si la consulta falla, muestra el error
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se recibieron IDs de productos válidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se recibieron los datos necesarios.']);
}
?>
