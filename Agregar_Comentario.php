<?php
session_start(); // Iniciar sesión
require 'conexion.php'; // Incluir el archivo de conexión

// Crear una instancia de la clase Database y obtener la conexión
$database = new Database();
$conexion = $database->getConnection();

// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario']; 

// Obtener los datos del formulario
$id_producto = $_POST['id_producto']; // ID del producto (pasado a través de POST)
$calificacion = $_POST['calificacion']; // Calificación (puede ser un número entre 1 y 5)
$comentario = $_POST['comentario']; // Comentario del usuario

// Validación de la calificación: Debe estar entre 1 y 5
if ($calificacion < 1 || $calificacion > 5) {
    echo "La calificación debe estar entre 1 y 5.";
    exit;
}

// Iniciar una transacción para asegurar la consistencia de los datos
$conexion->begin_transaction();

try {
    // 1. Insertar el comentario en la tabla Comentarios
    $sql_comentario = "INSERT INTO Comentarios (id_producto, id_usuario, calificacion, comentario) 
                       VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql_comentario);
    $stmt->bind_param("iiis", $id_producto, $id_usuario, $calificacion, $comentario);
    $stmt->execute();

    // 2. Actualizar la columna cantidad_comentarios en la tabla Productos
    $sql_actualizar_comentarios = "UPDATE Productos 
                                   SET cantidad_comentarios = cantidad_comentarios + 1 
                                   WHERE id_producto = ?";
    $stmt = $conexion->prepare($sql_actualizar_comentarios);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();

    // 3. Calcular el promedio de las calificaciones del producto
    $sql_calificacion_promedio = "SELECT AVG(calificacion) AS promedio 
                                  FROM Comentarios 
                                  WHERE id_producto = ?";
    $stmt = $conexion->prepare($sql_calificacion_promedio);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $promedio_calificacion = round($row['promedio'], 1); // Redondear a 1 decimal

    // 4. Actualizar la columna valoracion en la tabla Productos
    $sql_actualizar_valoracion = "UPDATE Productos 
                                  SET valoracion = ? 
                                  WHERE id_producto = ?";
    $stmt = $conexion->prepare($sql_actualizar_valoracion);
    $stmt->bind_param("di", $promedio_calificacion, $id_producto);
    $stmt->execute();

    // Si todo va bien, hacer commit de la transacción
    $conexion->commit();

    // Redirigir al producto (recargar la página)
    header("Location: Producto.php?id=" . $id_producto);
    exit; // Asegurarse de que el script se detenga después de la redirección

} catch (Exception $e) {
    // Si ocurre algún error, deshacer la transacción
    $conexion->rollback();

    // Mostrar error
    echo "Error al guardar el comentario: " . $e->getMessage();
}

// Cerrar la conexión
$conexion->close();
?>
