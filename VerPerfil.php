<?php
include_once 'apiUsuarios.php';
include 'navbar.php';



if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $api = new ApiUsuarios();
    $usuarios = $api->VerUsuario($id);
    if (isset($usuarios['items'][0])) {
        $nombre_usuario = $usuarios['items'][0]['nombre_usuario'];
        $correo = $usuarios['items'][0]['correo'];
        $sexo = $usuarios['items'][0]['sexo'];
        $avatar = $usuarios['items'][0]['avatar'];
        $fechaNac = $usuarios['items'][0]['fecha_nacimiento'];
        $rol = $usuarios['items'][0]['rol'];
        $publico_privado = $usuarios['items'][0]['publico_privado'];
    }
    
}
?>

<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    body{
        font-family : ;
    }
        .Privado{
    width: 500px;
    padding: 20px;
    height: 500px;
    background-color:#cfd0d5; 
    border-radius: 15px; 
    box-shadow: 0 10px 8px rgba(10, 0, 2, 0.1); 
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
    margin-left:500px;
    }
    .btn-privado {
            background-color: #6c757d; /* Gris de Bootstrap */
            border-color: #6c757d; /* Gris de Bootstrap */
        }

        .btn-privado i {
            margin-right: 5px; /* Espacio entre el ícono y el texto */
        }
    .img-Perfil{

        height: 100px;
        margin-left: 0px;
        margin-top: 50px;
        border-radius: 60%;
        overflow: hidden;
    }
    .user-info {
    margin-top: 20px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    color: #333;
    text-align: left;
    }

    .user-info p {
    margin: 10px 0;
    }

    h3 {
    font-family: Arial, sans-serif;
    font-size: 24px;
    margin-top: 20px;
    }

    #contenedor-Perfil {
    display: flex;
    justify-content: space-around; /* Ajusta la separación entre elementos */
    align-items: center;
    margin-top: 50px;
    }

    .Go-To-Listas, .Go-To-MisPedidos, .Go-To-MisProductos,.Go-To-ConsultaVentas, .Go-To-AutorizarPrds {
    width: 200px;
    padding: 20px;
    height: 200px;
    background-color: #cfd0d5;
    border-radius: 15px;
    box-shadow: 0 10px 8px rgba(10, 0, 2, 0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
    text-decoration: none;
    color: inherit;
    }

    .Go-To-Listas:hover, .Go-To-MisPedidos:hover, .Go-To-MisProductos:hover, .Go-To-AutorizarPrds:hover{
    transform: translateY(-5px);
    box-shadow: 0 15px 12px rgba(10, 0, 2, 0.2);
    }

    .img-Listas {
    height: 100px;
    margin-top: -20px;
    overflow: hidden;
    }

</style>
</head>
<body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<br> <br> <br> <br>



<div class="Privado">
    <img src="<?php echo $avatar; ?>" class="img-Perfil">
    <h3><?php echo $nombre_usuario; ?></h3>

    <?php if ($publico_privado == 1): ?>
        <div class="user-info">
            <p><strong>Correo electrónico:</strong> <?php echo $correo; ?></p>
            <p><strong>Fecha de Nacimiento:</strong> <?php echo $fechaNac; ?></p>
            <p><strong>Sexo:</strong> <?php echo $sexo; ?></p>

            <?php if ($rol == '1'): ?>
                <p><strong>Rol:</strong> Comprador</p>
            <?php endif; ?>

            <?php if ($rol == '2'): ?>
                <p><strong>Rol:</strong> Vendedor</p>
            <?php endif; ?>

            <?php if ($rol == '3'): ?>
                <p><strong>Administrador:</strong> Vendedor</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="private-icon">
            <i class="fas fa-user-lock fa-3x"></i> 
        </div>
    <?php endif; ?>
</div>

<div id="contenedor-Perfil">
        <?php if ($rol == '1' && $publico_privado == '1'): ?>
            <!-- Secciones visibles solo para Comprador -->
            <a href="VerListasUsuario.php?id_usuario=<?php echo $id; ?>" class="Go-To-Listas" role="button">
                <h3>Listas de Deseos</h3>
                <img src="Imagenes/Listas.png" alt="Miniatura 3" class="img-Listas">
            </a>

        <?php endif; ?>

        <?php if ($rol == '2' && $publico_privado == '1'): ?>
            <a href="ProductosVendedor.php?id_usuario=<?php echo $id; ?>" class="Go-To-MisProductos" role="button">                   <h3>Mis Productos</h3>
                <img src="Imagenes/MisProductos.png" alt="Miniatura 3" class="img-Listas">
            </a>
        <?php endif; ?>

        <?php if ($rol == '3'): ?>

        <?php endif; ?>



    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    
     
<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 


    <footer>
        <div class="footer-content">
          <p>&copy; Joshua Rogelio Rodriguez Valles</p>
          <p>&copy; Yaroslav de Jesus Nunez Gomez</p>
        </div>
      </footer>




    
</html>