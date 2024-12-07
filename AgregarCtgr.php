<?php
include_once 'conexion.php';
session_start();
$id = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $des = $_POST['descripcion'];
    $carpetaDestino = 'Imagenes/';
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombreImagen = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $rutaDestino = $carpetaDestino . $nombreImagen;

        // Mover la imagen a la carpeta de destino
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            // Si la imagen se mueve correctamente, procede a leer su contenido binario
            $imagenctg = file_get_contents($rutaDestino);
        } else {
            echo "Error al mover la imagen a la carpeta de destino.";
            exit();
        }
    } else {
        echo "Error al cargar la imagen.";
        exit();
    }



    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {        
        try {
        $query = "INSERT INTO categorias(nombre_categoria, descripcion, id_creador,imagen) 
        VALUES(?, ?, ?, ?)";

            $stmt = $conn->prepare($query);
           
            $stmt->bind_param("ssis", $nombre, $des, $id,$imagenctg);
            $stmt->execute();
            $stmt->close();
        }catch (mysqli_sql_exception $exception) {
            echo "ERROR: " . $exception->getMessage();
        }

    }else {
        echo "No se pudo establecer la conexión a la base de datos";
    }

    // Cerrar la conexión
    $database->cerrarConexion();


}

?>