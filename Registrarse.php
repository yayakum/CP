<?php
session_start();  
include 'conexion.php'; 

$db = new Database();
$correo = $_POST['email'];
$nombre = $_POST['fullName'];
$usuario = $_POST['username'];
$pass = $_POST['password'];
$fechaNac = $_POST['dob'];
$rol = $_POST['role'];
$gender = $_POST['gender'];
$generoN;

// Determinar el rol numérico
if ($rol == "administrador") {
    $rolN = 3;
} else if ($rol == "vendedor") {
    $rolN = 2;
} else if ($rol == "comprador") {
    $rolN = 1;
}

// Determinar el género
if ($gender == "M") {
    $generoN = 'M';
} else if ($gender == "F") {
    $generoN = 'F';
} else if ($gender == "Otro") {
    $generoN = 'Otro';
}

$queryCorreo = "SELECT COUNT(*) AS total FROM usuarios WHERE correo = ?";
$stmtCorreo = $db->conexion->prepare($queryCorreo);
$stmtCorreo->bind_param("s", $correo);
$stmtCorreo->execute();
$resultCorreo = $stmtCorreo->get_result();
$rowCorreo = $resultCorreo->fetch_assoc();
$correoExiste = $rowCorreo['total'];


if ($correoExiste > 0 ) {
    $mensaje = "";
    if ($correoExiste > 0) {
        $mensaje .= "El correo ya está registrado.\n";
    }
    
    echo "<script>
        alert('$mensaje');
        window.history.back();
    </script>";
    exit();
}

// Manejar el archivo de avatar
$avatar = $_FILES['avatar']['name'];
$temporal = $_FILES['avatar']['tmp_name'];
$carpeta = 'Imagenes';
$ruta = $carpeta . '/' . $avatar;
move_uploaded_file($temporal, $ruta);

// Insertar el nuevo usuario
$query = "INSERT INTO usuarios(nombre_completo, sexo, fecha_nacimiento, nombre_usuario, avatar, correo, contraseña, id_rol) 
          VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $db->conexion->prepare($query);
$stmt->bind_param("sssssssi", $nombre, $generoN, $fechaNac, $usuario, $ruta, $correo, $pass, $rolN);
$ejecutar = $stmt->execute();

if ($ejecutar) {
    $ultimoId = $db->conexion->insert_id;
    $_SESSION['id_usuario'] = $ultimoId;
    header("Location: index.php");
    exit();
} else {
    echo "Error en el registro: " . $db->conexion->error;
}
?>
