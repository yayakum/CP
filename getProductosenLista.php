<?php
//getooProductosenLista.php
session_start(); // Asegúrate de que la sesión esté iniciada
include_once 'apiUsuarios.php';

// Obtén el id_lista del parámetro de la URL
$id_lista = intval($_GET['id_lista']);

// Crea una instancia de ApiUsuarios
$apiUsuarios = new ApiUsuarios();

// Llama a tu función para obtener los productos de la lista
$productosEnLista = $apiUsuarios->getProductosEnLista($id_lista);

// Obtiene el nombre de la lista si es necesario
$nombre_lista = ""; // Si necesitas el nombre de la lista, puedes obtenerlo con otra función si es necesario

// Devuelve un JSON con los productos y el nombre de la lista
$response = [
    'nombre_lista' => $nombre_lista, // Puedes cambiar esto si decides obtener el nombre
    'productos' => $productosEnLista
];

header('Content-Type: application/json');
echo json_encode($response);
?>
