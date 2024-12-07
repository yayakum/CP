<?php
include_once 'conexion.php';
session_start();
$id = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre_producto'];
    $des = $_POST['product-description'];
    $CoV = $_POST['listing-type'];
    $categoria = $_POST['product-category'];
    $precio = $_POST['precio'];
    $stock = $_POST['cantidad_disponible'];
    

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        try {
            $query = "INSERT INTO productos(nombre_producto, descripcion, precio, cantidad_disponible, id_categoria, id_vendedor, estado, autorizado) 
            VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

                // Preparar la consulta
                $stmt = $conn->prepare($query);
                
                // Suponiendo que $autorizado es un entero, puedes usar un integer en lugar de string.
                $autorizado = 1; // Cambiado a integer
                $stmt->bind_param("ssdiissi", $nombre, $des, $precio, $stock, $categoria, $id, $CoV, $autorizado);
                $stmt->execute();

            // Obtener el ID del último producto insertado
            $idProducto = $conn->insert_id;

            // Inserción de imágenes
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                $imageName = $_FILES['imagenes']['name'][$key];
                $imageTmpName = $_FILES['imagenes']['tmp_name'][$key];
                $imagetype = $_FILES['imagenes']['type'][$key];
                $carpeta = 'Imagenes/Productos';
                $ruta = $carpeta . '/' . $imageName;
                move_uploaded_file($imageTmpName, $ruta);

                $bytesArchivo = file_get_contents($ruta);

                $query = "INSERT INTO contenido (id_producto, tipo_contenido, contenido) 
                          VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iss", $idProducto, $imagetype, $bytesArchivo);
                $stmt->execute();
            }

            // Inserción de videos
            foreach ($_FILES['video']['tmp_name'] as $key => $tmp_name) {
                $videoName = $_FILES['video']['name'][$key];
                $videoTmpName = $_FILES['video']['tmp_name'][$key];
                $videotype = $_FILES['video']['type'][$key];
                $carpeta = 'Videos';
                $ruta = $carpeta . '/' . $videoName;
                move_uploaded_file($videoTmpName, $ruta);

                $bytesArchivo = file_get_contents($ruta);

                $query = "INSERT INTO contenido (id_producto, tipo_contenido, contenido) 
                          VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iss", $idProducto, $videotype, $bytesArchivo);
                $stmt->execute();
            }

            // Cerrar la declaración
            $stmt->close();

            header("Location: ProductosVendedor.php");
            exit;
        } catch (mysqli_sql_exception $exception) {
            echo "ERROR: " . $exception->getMessage();
        }
    } else {
        echo "No se pudo establecer la conexión a la base de datos";
    }

    // Cerrar la conexión
    $database->cerrarConexion();
}
?>
