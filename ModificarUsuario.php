<?php
require_once 'conexion.php'; 

$database = new Database();
    $conexion = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario']; 
    $usuario = trim($_POST['usuario']);
    $nombre_completo = trim($_POST['nc']);
    $correo = trim($_POST['email']);
    $password = trim($_POST['password']);
    $fecha_nacimiento = $_POST['birthDate'];
    $sexo = $_POST['sexo'];
    $avatar_actual = $_POST['imagenActual'];

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $avatar_dir = "Imagenes/"; 
        $avatar_nombre = basename($_FILES['profileImage']['name']);
        $avatar_ruta = $avatar_dir . $avatar_nombre;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $avatar_ruta)) {
            $avatar = $avatar_ruta;
        } else {
            die("Error al subir la imagen.");
        }
    } else {
        $avatar = $avatar_actual; 
    }

    $sql = "UPDATE Usuarios SET 
            nombre_usuario = ?, 
            correo = ?, 
            contraseña = COALESCE(?, contraseña), 
            avatar = ?, 
            nombre_completo = ?, 
            fecha_nacimiento = ?, 
            sexo = ?
            WHERE id_usuario = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param(
            "sssssssi", 
            $usuario, 
            $correo, 
            $password, 
            $avatar, 
            $nombre_completo, 
            $fecha_nacimiento, 
            $sexo, 
            $id_usuario
        );

        if ($stmt->execute()) {
            echo "Perfil actualizado correctamente.";
            header ('Location: PerfilPublico.php');
        } else {
            echo "Error al actualizar el perfil: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $conexion->close();
} else {
    echo "Método no permitido.";
}
?>
