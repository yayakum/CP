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
        $publico_privado= $data['items'][0]['publico_privado'];
        $pass = $data['items'][0]['contraseña'];
        $nombre_completo = $data['items'][0]['nombre_completo'];


    } else {
        $nombre_usuario = 'Usuario no encontrado';
        $correo = 'Correo no disponible';
    }

    ob_start();
    $api->getCategorias(); 
    $CategoriasJSON = ob_get_clean();
    $Categorias = json_decode($CategoriasJSON, true);
} else {
    $nombre_usuario = 'No se ha iniciado sesión';
    $correo = 'Correo no disponible';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuyHub</title>
    <style>
        /* Variables */
        :root {
            --primary-color: #143ee0;
            --secondary-color: #f9f9f9;
            --accent-color: #3498db;
            --text-color: #333;
            --border-color: #e0e0e0;
        }

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color:  #143ee0;
            padding: 10px 20px;
            color: white;

        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-left: 10px;
            color : #143ee0;
        }

        /* Search Bar */
        .search-bar {
            display: flex;
            align-items: center;
            gap: 5px;
            width: 50%;
            margin-top: 20px; /* Ajusta el valor según lo necesites */

        }

        .search-bar form {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            background-color: #0f2e9e;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
        }

        .dropdown-menu a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .search-bar input {
            padding: 8px;
            border: none;
            border-radius: 4px 0 0 4px;
            width: 300px;
            box-sizing: border-box;
        }

        .search-bar button {
            padding: 8px 12px;
            background-color: #0f2e9e;
            border: none;
            border-radius: 0 4px 4px 0;
            color: white;
            cursor: pointer;
        }

        /* Navbar Items */
        .nav-items {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-n {
            padding: 8px 12px;
            background-color: #0f2e9e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cart-btn {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
                .search-bar .dropdown + .dropdown {
            margin-left: 10px; /* Espacio entre el botón de búsqueda y el menú de filtros */
        }

        .search-bar .dropdown-toggle {
            background-color: #0f2e9e;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
            margin-top: 5px; /* Espacio entre el botón de filtros y el menú desplegable */
        }

        .search-bar .dropdown-menu a {
            color: black;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .search-bar .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        .search-bar .dropdown:hover .dropdown-menu {
            display: block;
        }
        /* Secondary Navbar */
        .secondary-nav {
            display: flex;
            justify-content: center;
            background-color: #212529;
            padding: 5px 0;
        }

        .secondary-nav .nav-item {
            margin: 0 10px;
        }

        .secondary-nav .nav-link {
            color: white;
            text-decoration: none;
            padding: 5px;
        }

        .secondary-nav .nav-link:hover {
            text-decoration: underline;
            color : white;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <a class="logo" href="index.php">BuyHub</a>
    <div class="search-bar">
    <form action="ResultadosBusqueda.php" method="get" class="search-form">
    <input type="text" name="query" placeholder="Buscar..." required value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>">
    <button type="submit">Buscar</button>

    <!-- Menú de Filtros de Búsqueda -->
    
    <!-- Fin Menú de Filtros de Búsqueda -->
</form>


</div>

    <div class="nav-items">
    <?php if (!isset($_SESSION['id_usuario'])): ?>
    <button class="btn-n" onclick="location.href='InicioSesionVista.php'">Iniciar sesión</button>
    <button class="btn-n" onclick="location.href='RegistrarseVista.php'">Registrarse</button>
<?php endif; ?>
<?php if (isset($_SESSION['id_usuario'])): ?>
    <button class="btn-n cart-btn" onclick="location.href='Carrito.php'">
        🛒 Carrito
    </button>
<?php endif; ?>
<?php if (isset($_SESSION['id_usuario'])): ?>
        <div class="dropdown">
            <button class="dropdown-toggle profile">
                <img src="<?php echo $avatar; ?>" alt="Usuario" class="profile-img">
                <?php echo $nombre_usuario; ?>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="PerfilPublico.php">Perfil</a>
                <a class="dropdown-item" href="#">Configuraciones</a>
                <?php if ($rol == '2'): ?>
                    <a class="dropdown-item" href="AgregarProducto.php">Vender</a>
                    <a class="dropdown-item" href="CrearCategoria.php">Crear Categoria</a>
                <?php endif; ?>
                <a class="dropdown-item" href="cerrar_sesion.php">Cerrar sesión</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

</nav>
<div class="secondary-nav">
    <a class="nav-link" href="#">Tendencias</a>
    <a class="nav-link" href="#">Lo más nuevo</a>
    <a class="nav-link" href="#">Lo más vendido</a>
    <a class="nav-link" href="#">Rebajas</a>
</div>



    <script>
        function seleccionarCategoria(idCategoria) {
            document.getElementById('id_categoria').value = idCategoria;
        }
    </script>
</body>
</html>
