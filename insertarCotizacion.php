<?php
include_once 'conexion.php';
session_start();  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_producto'], $_POST['id_comprador'], $_POST['precio'], $_POST['cantidad'])) {
        $id_producto = $_POST['id_producto'];
        $id_comprador = $_POST['id_comprador'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $comentarios = $_POST['comentarios'] ?? ''; // Opcional, recoger comentarios

        $database = new Database();
        $conexion = $database->getConnection();

        // Iniciar transacción para asegurar consistencia
        $conexion->begin_transaction();

        try {
            // Insertar cotización
            $precio_total = $precio * $cantidad;
            $sql_cotizacion = "INSERT INTO Cotizaciones (id_producto, id_comprador, precio, estado) 
                               VALUES (?, ?, ?, 'pendiente')";
            
            $stmt_cotizacion = $conexion->prepare($sql_cotizacion);
            $stmt_cotizacion->bind_param('iid', $id_producto, $id_comprador, $precio_total);
            
            if (!$stmt_cotizacion->execute()) {
                throw new Exception('Error al insertar la cotización');
            }
            
            $id_cotizacion = $stmt_cotizacion->insert_id;
            $stmt_cotizacion->close();

            // Construir mensaje de cotización
            $mensaje_cotizacion = json_encode([
                'tipo' => 'cotizacion',
                'id_cotizacion' => $id_cotizacion,
                'precio_unitario' => $precio,
                'cantidad' => $cantidad,
                'precio_total' => $precio_total,
                'comentarios' => $comentarios,
                'id_producto' => $id_producto
            ]);

            // Insertar mensaje de cotización
            $id_remitente = $_SESSION['id_usuario'];
            $sql_mensaje = "INSERT INTO Mensajes (id_conversacion, id_remitente, contenido, tipo) 
                            VALUES ((SELECT id_conversacion FROM Conversaciones 
                                     WHERE id_producto = ? AND (id_comprador = ? OR id_vendedor = ?)), 
                                    ?, ?, 'cotizacion')";
            
            $stmt_mensaje = $conexion->prepare($sql_mensaje);
            $stmt_mensaje->bind_param('iiiss', 
                $id_producto, $id_comprador, $id_remitente, 
                $id_remitente, $mensaje_cotizacion
            );
            
            if (!$stmt_mensaje->execute()) {
                throw new Exception('Error al insertar el mensaje de cotización');
            }
            
            $stmt_mensaje->close();

            // Confirmar transacción
            $conexion->commit();

            echo json_encode([
                'success' => true, 
                'id_cotizacion' => $id_cotizacion, 
                'mensaje_cotizacion' => $mensaje_cotizacion
            ]);

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