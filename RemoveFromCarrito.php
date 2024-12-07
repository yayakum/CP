<?php
include_once 'conexion.php'; // Asegúrate de tener acceso a tu conexión

// Verifica que se haya enviado el ID del producto
if (isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    
    // Conexión a la base de datos
    $database = new Database();
    $conexion = $database->getConnection();
    // Eliminar el producto del carrito
    $query = "DELETE FROM Productos_en_carrito WHERE id_producto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_producto);
    
    if ($stmt->execute()) {
        // Opcional: Reinicia el autoincremento
        $conexion->query("ALTER TABLE Productos_en_carrito AUTO_INCREMENT = 1");

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID de producto no proporcionado']);
}
