<?php
session_start();
include_once 'apiUsuarios.php';

$api = new ApiUsuarios();

if(isset($_SESSION['id_usuario'])) {
    $id = $_SESSION['id_usuario'];
    
    // Obtener la información del usuario con ese ID
    $api->getbyID($id);

} else {
    $api->error('No se ha iniciado sesión');
}

?>