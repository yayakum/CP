<?php 
session_start(); 
include_once 'apiUsuarios.php';  

if (isset($_POST['correo']) && isset($_POST['contrasena'])) {     
    $correo = $_POST['correo'];     
    $contrasena = $_POST['contrasena'];      
    
    $api = new ApiUsuarios();     
    $res = $api->validarCredenciales($correo, $contrasena);      
    
    if (isset($res['id_usuario'])) {         
        $_SESSION['id_usuario'] = $res['id_usuario'];           
        $_SESSION['rol'] = $res['rol'];                   
        
        if (isset($_POST['recordarme'])) {             
            setcookie("correo", $correo, time() + (30 * 24 * 60 * 60), "/");             
            setcookie("contrasena", $contrasena, time() + (30 * 24 * 60 * 60), "/");         
        } else {             
            setcookie("correo", "", time() - 3600, "/");             
            setcookie("contrasena", "", time() - 3600, "/");         
        }          
        header("Location: index.php");     
    } else {
        // Redirigir de vuelta al formulario con mensaje de error
        header("Location: InicioSesionVista.php?error=1");
    }
} 
?>