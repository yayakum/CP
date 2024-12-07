<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Crear una instancia de la clase Database
$db = new Database();

// ID del producto que deseas mostrar
$productId = 20; // Cambia este valor según el producto que deseas consultar

// Consulta SQL para obtener las URLs de las imágenes del producto
$sql = "SELECT imagenes FROM productos WHERE id_producto = $productId";
$result = $db->ejecutarConsulta($sql);

if ($result->num_rows > 0) {
    // Obtener las URLs de imágenes en formato JSON
    $row = $result->fetch_assoc();
    $imageUrls = json_decode($row['imagenes'], true); // Decodifica el JSON a un array

    // Verificar si $imageUrls es un array válido
    if (is_array($imageUrls) && !empty($imageUrls)) {
        echo "<h2>Imágenes del producto</h2>";
        echo "<div style='display: flex; gap: 10px;'>";

        // Mostrar cada imagen
        foreach ($imageUrls as $imageUrl) {
            echo "<img src='$imageUrl' alt='Imagen del producto' style='width: 150px; height: auto;'>";
        }

        echo "</div>";
    } else {
        echo "No se encontraron imágenes para este producto o el JSON es inválido.";
    }
} else {
    echo "No se encontraron resultados para este producto.";
}

// Cerrar la conexión
$db->cerrarConexion();
?>
