<?php
session_start();
require 'conexion.php';

header('Content-Type: application/json');

$database = new Database();
$conexion = $database->getConnection();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['id_lista']) || !isset($_POST['listName']) || !isset($_POST['listDescription']) || !isset($_POST['visibility'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

try {
    $id_lista = intval($_POST['id_lista']);
    $nombre_lista = trim($_POST['listName']);
    $descripcion = trim($_POST['listDescription']);
    $publica_privada = intval($_POST['visibility']);
    $id_usuario = $_SESSION['id_usuario'];

    // Validaciones básicas
    if (empty($nombre_lista) || strlen($nombre_lista) > 100) {
        echo json_encode(['success' => false, 'message' => 'Nombre de lista inválido']);
        exit;
    }

    // Verificar que la lista pertenece al usuario
    $queryLista = "SELECT id_usuario FROM Listas WHERE id_lista = ?";
    $stmtLista = $conexion->prepare($queryLista);
    $stmtLista->bind_param("i", $id_lista);
    $stmtLista->execute();
    $resultLista = $stmtLista->get_result();

    if ($resultLista->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Lista no encontrada']);
        exit;
    }

    $lista_actual = $resultLista->fetch_assoc();
    if ($lista_actual['id_usuario'] != $id_usuario) {
        echo json_encode(['success' => false, 'message' => 'No tienes permiso para editar esta lista']);
        exit;
    }

    // Realizar la actualización
    $queryActualizar = "UPDATE Listas SET nombre_lista = ?, descripcion = ?, publica_privada = ? WHERE id_lista = ?";
    $stmtActualizar = $conexion->prepare($queryActualizar);
    $stmtActualizar->bind_param("ssii", $nombre_lista, $descripcion, $publica_privada, $id_lista);

    if ($stmtActualizar->execute()) {
        echo json_encode(['success' => true, 'message' => 'Lista actualizada correctamente']);
    } else {
        throw new Exception("Error al actualizar la lista: " . $stmtActualizar->error);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
} finally {
    $database->cerrarConexion();
}
?>
