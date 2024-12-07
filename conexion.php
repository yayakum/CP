<?php
class Database {
    private $host = "localhost";
    private $usuario = "root";
    private $contraseña = "12345";
    private $baseDeDatos = "CapaIntermedia";
    public $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contraseña, $this->baseDeDatos);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function getConnection() {
        return $this->conexion;
    }
    
    public function ejecutarConsulta($query) {
        return $this->conexion->query($query);
    }

    public function cerrarConexion() {
        $this->conexion->close();
    }
}
?>
