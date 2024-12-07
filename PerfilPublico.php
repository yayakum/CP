<?php
include_once 'apiUsuarios.php';
include 'navbar.php';





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
    .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .image-upload {
        display: block;
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
        position: relative;
        cursor: pointer;
    }

    .image-upload img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .image-upload input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    #profileForm input,
    #profileForm select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    #profileForm button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-picture {
            margin-right: 0;
            margin-bottom: 20px;
        }

        .filter-group {
            margin-bottom: 20px;
        }
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 20px;
    }

    .button-group button {
        flex: 1;
        padding: 10px;
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

    .Go-To-Listas:hover, .Go-To-MisPedidos:hover, .Go-To-MisProductos:hover, .Go-To-AutorizarPrds:hover,.Go-To-ConsultaVentas:hover{
    transform: translateY(-5px);
    box-shadow: 0 15px 12px rgba(10, 0, 2, 0.2);
    }

    .img-Listas {
    height: 100px;
    margin-top: -20px;
    overflow: hidden;
    .error {
    border-color: red;
    }

    .error-message {
        color: red;
        font-size: 0.9em;
        margin-top: 4px;
    }

    }

</style>
</head>
<body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<br> <br> <br> <br>



<div class="Privado">
    <div class="col-md-5 d-flex align-items-end">
    
        <form id="form-cambiar-privacidad">
            <input type="hidden" name="user_id" value="<?php echo $id; ?>">
            
            <button type="button" class="btn btn-secondary text-muted btn-cambiar-privado" onclick="cambiarPrivacidad()">
                <i id="icono-boton" class="fas <?php echo ($publico_privado == 1) ? 'fa-user-lock' : 'fa-user'; ?>"></i> 
                <span id="texto-boton">
                    <?php echo ($publico_privado == 1) ? 'Cambiar a privado' : 'Cambiar a público'; ?>
                </span>
            </button>
        </form>



    </div>

        

    <img src="<?php echo $avatar; ?>" id="profilePicture" class="img-Perfil">
    <h3><?php echo $nombre_usuario; ?></h3>
    <div class="user-info">
        
        <p><strong>Correo electrónico:</strong> <?php echo $correo; ?></p>
        <p><strong>Contraseña:</strong> **********</p>
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
</div>
<div id="contenedor-Perfil">
        <?php if ($rol == '1'): ?>
            <!-- Secciones visibles solo para Comprador -->
            <a href="Listas.php" class="Go-To-Listas" role="button">
                <h3>Listas de Deseos</h3>
                <img src="Imagenes/Listas.png" alt="Miniatura 3" class="img-Listas">
            </a>

            <a href="MisPedidos.php" class="Go-To-MisPedidos" role="button">   
                <h3>Mis Pedidos</h3>
                <img src="Imagenes/MisPedidos.png" alt="Miniatura 3" class="img-Listas">
            </a>
        <?php endif; ?>

        <?php if ($rol == '2'): ?>
            <!-- Secciones visibles solo para Vendedor -->
            <a href="ProductosVendedor.php" class="Go-To-MisProductos" role="button">   
                <h3>Mis Productos</h3>
                <img src="Imagenes/MisProductos.png" alt="Miniatura 3" class="img-Listas">
            </a>

            <a href="ConsultaVentas.php" class="Go-To-ConsultaVentas" role="button">   
                <h3>Mis Ventas</h3>
                <img src="Imagenes/Ventas.png" alt="Miniatura 3" class="img-Listas">
            </a>

            <a href="Cotizacion.php?es_vendedor=1" class="Go-To-ConsultaVentas" role="button">   
                <h3>Cotizaciones</h3>
                <img src="Imagenes/Cotizaciones.png" alt="Miniatura 3" class="img-Listas">
            </a>

        <?php endif; ?>

        <?php if ($rol == '3'): ?>
            
            <a href="AutorizarProductos.php" class="Go-To-AutorizarPrds" role="button">   
                <h3>Autorizar Productos</h3>
                <img src="Imagenes/a.png" alt="Miniatura 3" class="img-Listas">
            </a>
        <?php endif; ?>



    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    
     
<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
<!-- Modal de Perfil -->
<div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Perfil de Usuario</h2>
            <form id="profileForm" action="ModificarUsuario.php" method="POST" enctype="multipart/form-data" >
                <label for="profileImage" class="image-upload">
                    <img src="<?php echo $avatar; ?>" id="profileImagePreview" alt="Imagen de Perfil">
                    <input type="file" id="profileImage" name="profileImage" accept="image/*">
                </label>
                <input type="hidden" name="imagenActual" value="<?php echo $avatar; ?>">

                <?php if ($rol == '1'): ?>
                <input type="text" id="rol" name="rol" placeholder="rol" value="Comprador" readonly>
                <?php endif; ?>
                <?php if ($rol == '2'): ?>
                <input type="text" id="rol" name="rol" placeholder="rol" value="Vendedor" readonly>
                <?php endif; ?>
                <input type="text" id="usuario" name="usuario" placeholder="usuario" value="<?php echo $nombre_usuario; ?>" >

                <input type="text" id="nc" name="nc" placeholder="nc" value="<?php echo $nombre_completo; ?>" >
                <input type="text" id="email" name="email" placeholder="Correo Electrónico" value="<?php echo $correo; ?>" >
                <input type="password" id="password" name="password" placeholder="Contraseña" value="<?php echo $pass; ?>">
                 <input type="date" id="birthDate" name="birthDate" value="<?php echo $fechaNac; ?>">
                 <input type="text" id="sexo" name="sexo" placeholder="sexo" value="<?php echo $sexo; ?>" readonly>
                 <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                <div class="button-group">
                    <button type="submit" class="btn-primary">Modificar</button>

                    <button type="button" id="cancelProfileBtn" class="btn-secondary">Cerrar</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <div class="footer-content">
          <p>&copy; Joshua Rogelio Rodriguez Valles</p>
          <p>&copy; Yaroslav de Jesus Nunez Gomez</p>
        </div>
      </footer>







<script>
    document.getElementById('profileForm').addEventListener('submit', function (event) {
    let valid = true;

    // Validar nombre de usuario
    const usuario = document.getElementById('usuario').value.trim();
    if (usuario.length < 3 || /\s/.test(usuario)) {
        mostrarError('usuario', 'El nombre de usuario debe tener al menos 3 caracteres y no contener espacios.');
        valid = false;
    } else {
        marcarCampoValido('usuario');
    }

    // Validar nombre completo
    const nombreCompleto = document.getElementById('nc').value.trim();
    const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,50}$/;
    if (!nombreRegex.test(nombreCompleto)) {
        mostrarError('nc', 'El nombre completo debe tener entre 3 y 50 caracteres y solo letras con acentos.');
        valid = false;
    } else {
        marcarCampoValido('nc');
    }

    // Validar correo electrónico
    const email = document.getElementById('email').value.trim();
    const dominiosPermitidos = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
    if (email.includes(' ') || !email.includes('@')) {
        mostrarError('email', 'El correo electrónico no debe contener espacios y debe tener un formato válido.');
        valid = false;
    } else {
        const partes = email.split('@');
        const dominio = partes[1];
        if (!dominiosPermitidos.includes(dominio)) {
            mostrarError('email', 'Por favor, usa un correo con dominio válido: gmail.com, hotmail.com, yahoo.com, outlook.com.');
            valid = false;
        } else {
            marcarCampoValido('email');
        }
    }

    // Validar contraseña
    const password = document.getElementById('password').value;
    const passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,30}$/;
    if (!passwordRegex.test(password)) {
        mostrarError('password', 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, un número y un carácter especial.');
        valid = false;
    } else {
        marcarCampoValido('password');
    }

    // Detener envío del formulario si hay errores
    if (!valid) {
        event.preventDefault();
    }
});

// Función para mostrar mensajes de error
function mostrarError(campoId, mensaje) {
    const campo = document.getElementById(campoId);
    campo.classList.add('error');
    let errorMensaje = campo.nextElementSibling;
    if (!errorMensaje || !errorMensaje.classList.contains('error-message')) {
        errorMensaje = document.createElement('div');
        errorMensaje.classList.add('error-message');
        campo.parentNode.insertBefore(errorMensaje, campo.nextSibling);
    }
    errorMensaje.textContent = mensaje;
}

// Función para marcar campo como válido
function marcarCampoValido(campoId) {
    const campo = document.getElementById(campoId);
    campo.classList.remove('error');
    const errorMensaje = campo.nextElementSibling;
    if (errorMensaje && errorMensaje.classList.contains('error-message')) {
        errorMensaje.remove();
    }
}

    </script>


 <script>
    document.querySelector(".btn-cambiar-privado").addEventListener("click", function () {
        const nuevoEstado = document.getElementById("texto-boton").textContent.includes("privado") ? 0 : 1;

        fetch('Privado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ publico_privado: nuevoEstado, user_id: <?php echo $id; ?> })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cambia el texto del botón y el icono basado en el nuevo estado
                const textoBoton = document.getElementById("texto-boton");
                const iconoBoton = document.getElementById("icono-boton");

                if (nuevoEstado === 0) {
                    textoBoton.textContent = 'Cambiar a público';
                    iconoBoton.classList.remove('fa-user-lock');
                    iconoBoton.classList.add('fa-user');
                } else {
                    textoBoton.textContent = 'Cambiar a privado';
                    iconoBoton.classList.remove('fa-user');
                    iconoBoton.classList.add('fa-user-lock');
                }
            } else {
                alert(data.message || "Error al cambiar el estado de privacidad.");
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('profileModal');
    const profilePicture = document.getElementById('profilePicture');
    const closeBtn = document.getElementsByClassName('close')[0];
    const cancelProfileBtn = document.getElementById('cancelProfileBtn');
    const profileImageInput = document.getElementById('profileImage');
    const profileImagePreview = document.getElementById('profileImagePreview');

    // Variable para guardar la imagen actual
    let originalImageSrc = profileImagePreview.src;

    // Abrir el modal al hacer clic en la imagen de perfil
    profilePicture.onclick = function() {
        modal.style.display = 'block';
        originalImageSrc = profileImagePreview.src; // Guardar la imagen original
    };

    // Cerrar el modal al hacer clic en la X
    closeBtn.onclick = function() {
        closeModal();
    };

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    };

    // Cancelar los cambios y cerrar el modal
    cancelProfileBtn.onclick = function() {
        closeModal();
    };

    // Mostrar la vista previa de la imagen seleccionada
    profileImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImagePreview.src = e.target.result; // Actualizar la fuente de la imagen
            };
            reader.readAsDataURL(file);
        }
    });

    function closeModal() {
        // Restaurar la imagen original si no se guarda
        profileImagePreview.src = originalImageSrc;
        modal.style.display = 'none';
    }
});

    </script>



    
</html>