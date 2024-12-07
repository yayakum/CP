<?php
session_start();
require 'conexion.php'; 

$database = new Database();
$conexion = $database->getConnection();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nombre_lista = $_POST['listName'];
$descripcion = $_POST['listDescription'];
$publica_privada = (int)$_POST['visibility']; // Convertir directamente a entero

try {
    $queryCrearLista = "INSERT INTO Listas (nombre_lista, descripcion, publica_privada, id_usuario) VALUES (?, ?, ?, ?)";
    $stmtCrearLista = $conexion->prepare($queryCrearLista);
    $stmtCrearLista->bind_param("ssii", $nombre_lista, $descripcion, $publica_privada, $id_usuario);

    if ($stmtCrearLista->execute()) {
        $id_lista = $stmtCrearLista->insert_id;
        echo json_encode(['success' => true, 'id_lista' => $id_lista]);
    } else {
        throw new Exception("Error al crear la lista: " . $stmtCrearLista->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $database->cerrarConexion();
}
