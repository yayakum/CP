<?php
session_start();
require 'conexion.php';
require 'apiUsuarios.php';

$database = new Database();
$conexion = $database->getConnection();

$id_usuario = $_SESSION['id_usuario'];

$apiUsuarios = new ApiUsuarios();

// Verificamos si paypal_order_id está presente en la URL
if (isset($_GET['paypal_order_id'])) {
    // Recuperamos el id_producto desde la sesión
    if (isset($_SESSION['id_producto'])) {
        $id_producto = $_SESSION['id_producto'];
    } else {
        echo "Error: id_producto no encontrado en la sesión.";
        exit();
    }

    $total = $_GET['total']; 
    $cantidad = $_GET['cantidad'] ?? 1; 

    if (empty($id_producto) || empty($total) || empty($cantidad)) {
        echo "Error: Falta información de producto, cantidad o total.";
        exit();
    }

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        $queryVenta = "INSERT INTO Ventas (id_usuario_comprador, total) VALUES (?, ?)";
        if ($stmt = $conexion->prepare($queryVenta)) {
            $stmt->bind_param("id", $id_usuario, $total); 
            $stmt->execute();
            $id_venta = $conexion->insert_id;  
            $stmt->close();
        } else {
            throw new Exception("Error al preparar la consulta de ventas.");
        }

        $queryProducto = "SELECT precio, cantidad_disponible FROM Productos WHERE id_producto = ?";
        if ($stmt = $conexion->prepare($queryProducto)) {
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($precio, $cantidad_disponible);
                $stmt->fetch();
                
                // Verificar si hay suficiente stock
                if ($cantidad_disponible < $cantidad) {
                    throw new Exception("No hay suficiente stock disponible.");
                }
            } else {
                throw new Exception("Error: Producto no encontrado.");
            }
            $stmt->close();
        } else {
            throw new Exception("Error al preparar la consulta de producto.");
        }

        $queryProductoVendido = "INSERT INTO Productos_vendidos (id_venta, id_producto, cantidad, precio) 
                                VALUES (?, ?, ?, ?)";
        if ($stmt = $conexion->prepare($queryProductoVendido)) {
            $stmt->bind_param("iiii", $id_venta, $id_producto, $cantidad, $precio);  
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error al preparar la consulta de productos vendidos.");
        }

        // Actualizar la cantidad disponible
        $queryUpdateStock = "UPDATE Productos SET cantidad_disponible = cantidad_disponible - ? 
                            WHERE id_producto = ? AND cantidad_disponible >= ?";
        if ($stmt = $conexion->prepare($queryUpdateStock)) {
            $stmt->bind_param("iii", $cantidad, $id_producto, $cantidad);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error al actualizar el inventario.");
            }
            $stmt->close();
        } else {
            throw new Exception("Error al preparar la consulta de actualización de stock.");
        }

        // Confirmar transacción
        $conexion->commit();

        // Limpiar el id_producto de la sesión después de procesar la compra
        unset($_SESSION['id_producto']);

        // Redirigir a la página CompraRealizada con el id_venta
        header("Location: CompraRealizada.php?id_venta=$id_venta");
        exit();
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    // Si no está en el flujo de Paypal, procesamos los productos del carrito
    $carrito = $apiUsuarios->getCarrito($id_usuario);

    if (count($carrito) > 0) {
        $total = 0;
        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        $total += 97.00;

        // Iniciar transacción
        $conexion->begin_transaction();

        try {
            // Inserción en la tabla Ventas
            $queryVenta = "INSERT INTO Ventas (id_usuario_comprador, total) VALUES (?, ?)";
            if ($stmt = $conexion->prepare($queryVenta)) {
                $stmt->bind_param("id", $id_usuario, $total);
                $stmt->execute();
                $id_venta = $conexion->insert_id;
                $stmt->close();
            } else {
                throw new Exception("Error al preparar la consulta de ventas.");
            }

            // Verificar stock y procesar cada producto
            foreach ($carrito as $producto) {
                // Verificar stock disponible
                $queryStock = "SELECT cantidad_disponible FROM Productos WHERE id_producto = ?";
                if ($stmt = $conexion->prepare($queryStock)) {
                    $stmt->bind_param("i", $producto['id_producto']);
                    $stmt->execute();
                    $stmt->bind_result($cantidad_disponible);
                    $stmt->fetch();
                    $stmt->close();

                    if ($cantidad_disponible < $producto['cantidad']) {
                        throw new Exception("No hay suficiente stock para el producto ID: " . $producto['id_producto']);
                    }
                }

                // Insertar en Productos_vendidos
                $queryProductoVendido = "INSERT INTO Productos_vendidos (id_venta, id_producto, cantidad, precio) 
                                       VALUES (?, ?, ?, ?)";
                if ($stmt = $conexion->prepare($queryProductoVendido)) {
                    $stmt->bind_param("iiii", $id_venta, $producto['id_producto'], $producto['cantidad'], $producto['precio']);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    throw new Exception("Error al preparar la consulta de productos vendidos.");
                }

                // Actualizar stock
                $queryUpdateStock = "UPDATE Productos SET cantidad_disponible = cantidad_disponible - ? 
                                   WHERE id_producto = ? AND cantidad_disponible >= ?";
                if ($stmt = $conexion->prepare($queryUpdateStock)) {
                    $stmt->bind_param("iii", $producto['cantidad'], $producto['id_producto'], $producto['cantidad']);
                    $stmt->execute();
                    if ($stmt->affected_rows == 0) {
                        throw new Exception("Error al actualizar el inventario del producto ID: " . $producto['id_producto']);
                    }
                    $stmt->close();
                } else {
                    throw new Exception("Error al preparar la consulta de actualización de stock.");
                }
            }

            // Limpiar el carrito
            $queryEliminarCarrito = "DELETE FROM Productos_en_carrito WHERE id_carrito = (SELECT id_carrito FROM Carrito WHERE id_usuario = ?)";
            if ($stmt = $conexion->prepare($queryEliminarCarrito)) {
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $stmt->close();
            }

            // Confirmar transacción
            $conexion->commit();

            // Redirigir a la página CompraRealizada
            header("Location: CompraRealizada.php?id_venta=$id_venta");
            exit();
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conexion->rollback();
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Error: No hay productos en el carrito.";
        exit();
    }
}
?>