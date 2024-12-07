<?php
session_start();
require 'conexion.php'; // Incluir el archivo de conexión

// Crear una instancia de la clase Database y obtener la conexión
$database = new Database();
$conexion = $database->getConnection();

$id_usuario = $_SESSION['id_usuario']; // Asegúrate de que tienes el ID del usuario en la sesión
$id_producto = $_POST['id_producto'];
$cantidad = 1; // Puedes ajustar esto según sea necesario

$queryCarrito = "SELECT id_carrito FROM Carrito WHERE id_usuario = ?";
$stmtCarrito = $conexion->prepare($queryCarrito);
$stmtCarrito->bind_param("i", $id_usuario);
$stmtCarrito->execute();
$resultCarrito = $stmtCarrito->get_result();

if ($resultCarrito->num_rows === 0) {
    // Si no hay carrito, crea uno nuevo
    $queryNuevoCarrito = "INSERT INTO Carrito (id_usuario) VALUES (?)";
    $stmtNuevoCarrito = $conexion->prepare($queryNuevoCarrito);
    $stmtNuevoCarrito->bind_param("i", $id_usuario);
    $stmtNuevoCarrito->execute();
    
    // Obtener el nuevo ID del carrito
    $id_carrito = $conexion->insert_id;
} else {
    // Obtener el ID del carrito existente
    $rowCarrito = $resultCarrito->fetch_assoc();
    $id_carrito = $rowCarrito['id_carrito'];
}

// Verificar si el producto ya está en el carrito
$queryProductoCarrito = "SELECT cantidad FROM Productos_en_carrito WHERE id_carrito = ? AND id_producto = ?";
$stmtProductoCarrito = $conexion->prepare($queryProductoCarrito);
$stmtProductoCarrito->bind_param("ii", $id_carrito, $id_producto);
$stmtProductoCarrito->execute();
$resultProductoCarrito = $stmtProductoCarrito->get_result();

if ($resultProductoCarrito->num_rows > 0) {
    // Si el producto ya está en el carrito, actualizar la cantidad
    $rowProductoCarrito = $resultProductoCarrito->fetch_assoc();
    $nuevaCantidad = $rowProductoCarrito['cantidad'] + $cantidad;

    $queryActualizarCantidad = "UPDATE Productos_en_carrito SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?";
    $stmtActualizarCantidad = $conexion->prepare($queryActualizarCantidad);
    $stmtActualizarCantidad->bind_param("iii", $nuevaCantidad, $id_carrito, $id_producto);
    $stmtActualizarCantidad->execute();
} else {
    // Si el producto no está en el carrito, agregarlo
    $queryAgregarProducto = "INSERT INTO Productos_en_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)";
    $stmtAgregarProducto = $conexion->prepare($queryAgregarProducto);
    $stmtAgregarProducto->bind_param("iii", $id_carrito, $id_producto, $cantidad);
    $stmtAgregarProducto->execute();
}

// Cerrar la conexión (opcional, si deseas cerrar explícitamente)
$database->cerrarConexion();

// Redireccionar o enviar respuesta
header("Location: carrito.php"); // Redirigir al carrito o a donde necesites
exit();
?>
