<?php
session_start();
require 'conexion.php'; 

$database = new Database();
$conexion = $database->getConnection();

if (isset($_POST['id_producto']) && isset($_POST['listas'])) {
    $id_producto = $_POST['id_producto'];
    $listas = $_POST['listas'];

    $query = "INSERT INTO Productos_en_lista (id_lista, id_producto) VALUES (?, ?)";
    $stmt = $conexion->prepare($query);

    foreach ($listas as $id_lista) {
        $stmt->bind_param("ii", $id_lista, $id_producto);
        if (!$stmt->execute()) {
            
            exit();
        }
    }
    $stmt->close();
    
    echo json_encode(['success' => true]);
    header("Location: Listas.php");

} else {
    echo json_encode(['success' => false, 'message' => 'Datos no válidos']);
}
?>
