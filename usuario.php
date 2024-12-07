<?php

include_once 'conexion.php'; 

class Usuario extends Database {

    function obtenerUsuarios() {
        $query = "SELECT * FROM usuarios";
        return $this->ejecutarConsulta($query); 
    }

    function obtenerCategorias() {
        $query = "SELECT id_categoria, nombre_categoria, imagen FROM categorias";
        return $this->ejecutarConsulta($query); 
    }


    
    public function obtenerUsuario($id) {
        $query = "SELECT * FROM Usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function obtenerListas($id){
        $query = "SELECT * FROM Listas WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    

    function validarCredenciales($correo, $contrasena) {
    $query = "SELECT * FROM usuarios WHERE correo = '$correo' AND contraseña = '$contrasena'";
    return $this->ejecutarConsulta($query);
    }   

    function obtenerProductosbyID($id){
        $query = "SELECT * FROM productos WHERE id_vendedor = $id";
        return $this->ejecutarConsulta($query); 
    
    }

    

    
}

?>
