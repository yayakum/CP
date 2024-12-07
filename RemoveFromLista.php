<?php
// Eliminar producto de la lista
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si se han recibido los valores de id_producto y id_lista
    if (isset($_POST['id_producto']) && isset($_POST['id_lista'])) {
        $id_producto = $_POST['id_producto'];
        $id_lista = $_POST['id_lista'];

        include('conexion.php');
        $conexion = new Database();
        $conn = $conexion->getConnection();

        // Consulta para eliminar el producto de la lista
        $query = "DELETE FROM Productos_en_lista WHERE id_producto = ? AND id_lista = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_producto, $id_lista);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($stmt->execute()) {
            // Redirigir a la página de la lista con los productos restantes
            header("Location: ProductosLista.php?id_lista=$id_lista");
            exit(); // Asegúrate de llamar a exit() después de header()
        } else {
            echo "Error al eliminar el producto de la lista.";
        }
    } else {
        echo "Error: Datos incompletos.";
    }
}
?>
