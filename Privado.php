<?php
session_start();
require 'conexion.php';

$database = new Database();
$conexion = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_usuario'])) {
    $user_id = $_SESSION['id_usuario'];

    // Primero obtenemos el valor actual de publico_privado
    $query = "SELECT publico_privado FROM Usuarios WHERE id_usuario = ?";
    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($publico_privado_actual);
        $stmt->fetch();
        $stmt->close();

        // Alternamos el valor de publico_privado
        $nuevo_estado = ($publico_privado_actual == 1) ? 0 : 1;

        // Ejecutamos la actualización con el nuevo valor
        $query = "UPDATE Usuarios SET publico_privado = ? WHERE id_usuario = ?";
        if ($stmt = $conexion->prepare($query)) {
            $stmt->bind_param("ii", $nuevo_estado, $user_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'reload' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de actualización.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al obtener el estado actual.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no permitida o sesión no iniciada.']);
}

$conexion->close();
?>
