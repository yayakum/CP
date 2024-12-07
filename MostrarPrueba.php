<?php
session_start();
include_once 'apiUsuarios.php';

if (isset($_SESSION['id_usuario'])) {
    $id = $_SESSION['id_usuario'];
    
    $api = new ApiUsuarios();
    $data = $api->getbyID($id);

    if (isset($data['items'][0])) {
        $nombre_usuario = $data['items'][0]['nombre_usuario'];
        $correo = $data['items'][0]['correo'];
        $sexo = $data['items'][0]['sexo'];
        $avatar = $data['items'][0]['avatar'];
        $fechaNac = $data['items'][0]['fecha_nacimiento'];
        $rol = $data['items'][0]['rol'];

    } else {
        $nombre_usuario = 'Usuario no encontrado';
        $correo = 'Correo no disponible';
    }
} else {
    $nombre_usuario = 'No se ha iniciado sesión';
    $correo = 'Correo no disponible';
}
?>

<?php
include_once 'apiUsuarios.php';
?>
<div id="user-info">
  <!-- Aquí se mostrará el nombre del usuario -->
  Cargando...
</div>

<script>
  fetch('apiUsuarios.php?id_usuario=' + encodeURIComponent(<?php echo $_SESSION['id_usuario']; ?>), {
    method: 'GET',
    credentials: 'include' 
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Error en la respuesta de la API');
    }
    return response.json(); // Convertir la respuesta en JSON
  })
  .then(data => {
    console.log(data); // Verifica los datos en la consola
    if (data.items && data.items.length > 0) {
      document.getElementById('user-info').innerText = `Bienvenido, ${data.items[0].nombre_usuario}`;
    } else {
      document.getElementById('user-info').innerText = 'No se ha iniciado sesión';
    }
  })
  .catch(error => {
    console.error('Error al obtener la información del usuario:', error);
    document.getElementById('user-info').innerText = 'Error al obtener la información del usuario';
  });
</script>
