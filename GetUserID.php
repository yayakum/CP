<?php
session_start(); // Asegúrate de que la sesión esté iniciada

// Verifica si el usuario está logueado y tiene un ID de usuario
if (isset($_SESSION['id_usuario'])) {
    echo json_encode(array('id_usuario' => $_SESSION['id_usuario']));
} else {
    echo json_encode(array('error' => 'Usuario no logueado'));
}
?>
