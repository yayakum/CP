<?php
ob_start();

require_once 'apiUsuarios.php';
include 'navbar.php';

if (!isset($_SESSION['id_usuario'])) {
    // Redirige a Registrarse.php
    header("Location: RegistrarseVista.php");
    exit; // Detiene la ejecución después de la redirección
}

$id_usuario = $_SESSION['id_usuario'];
$api = new ApiUsuarios();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto = $_GET['id']; // Obtiene el ID del producto

    // Captura el JSON de salida de la función en un buffer
    ob_start();
    $api->getProductoByIDproducto($id_producto);
    $json_data = ob_get_clean();

    $producto = json_decode($json_data, true);
 
    if (!empty($producto)) {
        $nombre_producto = $producto['nombre_producto'];
        $descripcion = $producto['descripcion'];
        $cantidad = $producto['cantidad_disponible'];
        $precio = $producto['precio'];
        $imagenes = $producto['imagenes']; 
        $estado = $producto['estado'];  
        $video = $producto['video'];
        $id_vendedor = $producto['id_vendedor'];
        $categoria = $producto['id_categoria'];
        $ncategoria = $producto['nombre_categoria'];

        $max_cantidad = floor($cantidad * 0.10);  

        $max_cantidad = ($max_cantidad < 1) ? 1 : $max_cantidad;
        $ha_comprado = $api->haCompradoProducto($id_producto, $id_usuario);
        // Obtener productos relacionados
        ob_start();
        $response_related = $api->getProductosbyID($id_vendedor);
        $related_products_data = ob_get_clean();
        $productos_relacionados = json_decode($related_products_data, true);

        $filtered_related_products = array_filter($productos_relacionados['items'], function($item) use ($id_producto) {
            return $item['id_producto'] != $id_producto;
        });

        // Obtener listas del usuario
        $listas = $api->getListasByUsuario($id_usuario);

        // Verifica que la respuesta sea un arreglo
        if (!is_array($listas)) {
            $listas = []; // Asegúrate de que sea un arreglo vacío si no es un arreglo
        }
   
            // Verificar si 'items' existe en el arreglo de listas
        $listas_items = isset($listas['items']) ? $listas['items'] : [];

        ob_start();
        $api->getComentarios($id_producto);
        $json_comentarios = ob_get_clean();
        $comentarios = json_decode($json_comentarios, true);

        ob_start();
        $api->getValoracionProducto($id_producto);
        $json_valoracion = ob_get_clean();

        $valoracion_data = json_decode($json_valoracion, true);

        if (!empty($valoracion_data)) {
            $valoracion = $valoracion_data['valoracion'];
            $cantidad_comentarios = $valoracion_data['comentarios'];
        } else {
            $valoracion = 0;
            $cantidad_comentarios = 0;
        }

    } else {
        echo "Producto no encontrado.";
    }
}   

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
       
        .product-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        .product-images {
            position: relative;
            max-width: 600px; /* Limitar el ancho máximo del contenedor */
            margin: 0 auto;   /* Centrar el contenedor */
        }

            .main-image-container {
                position: relative;
                overflow: hidden;
                cursor: zoom-in;
                height: 400px; /* Ajusta la altura del contenedor según lo desees */
            }

        .main-image {
            width: 100%; /* La imagen ocupa todo el ancho del contenedor */
            height: auto; /* Mantiene la proporción de la imagen */
            max-height: 100%; /* Limitar la altura máxima para evitar que la imagen sea demasiado grande */
            object-fit: cover; /* Ajusta la imagen sin distorsionar */
            display: block;
        }
        .thumbnail-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .thumbnail.active {
            border-color: #007bff;
        }
        .product-info h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .price {
            font-size: 22px;
            font-weight: bold;
            color: #B12704;
            margin-bottom: 10px;

        }
        .availability {
            color: #28a745;
            margin-bottom: 10px;
        }
        .rating {
            color: #ffc107;
            margin-bottom: 10px;
        }
  
        .video-container {
            margin-top: 10px;
            width: 100%;
        }
        .video-container video {
            width: 100%;
            max-width: 600px;
            height: auto;
            margin-top: -130px;

        }
                
                .rating-container {
            display: flex;
            align-items: center;
            font-size: 1.5em;
            color: #ddd; /* Color de las estrellas vacías */
        }

        .rating-stars {
            position: relative;
            display: inline-block;
            color: #ddd; /* Color de fondo de las estrellas vacías */
            overflow: hidden;
        }

        .rating-stars::before {
            content: "★★★★★";
            position: absolute;
            top: 0;
            left: 0;
            width: calc(var(--rating) / 5 * 100%);
            color: #FFD700; /* Color de las estrellas llenas */
            overflow: hidden;
            white-space: nowrap;
        }

        

        .description {
            margin-top: 20px; /* Espacio superior para separar de otros elementos */
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0 20px; /* Ajusta el espacio horizontal alrededor del texto */
            height: 40px; /* Altura fija de 60px para todos los botones */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-align: center;
            display: flex; /* Para centrar el texto vertical y horizontalmente */
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid #007bff;
            color: #007bff;
        }
        .list-image{
            height: 40px;
        }

        .btn:hover {
            opacity: 0.8;
        }
        .related-products {
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        }

        .related-products h2 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #333;
        }

        .related-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px; /* Espaciado entre los productos */
        }

        .related-product {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            transition: transform 0.3s;
        }

    
        .related-product img {
            max-width: 40%;
            height: auto; /* Asegura que la imagen mantenga su proporción */
            border-radius: 5px;

        }

        .related-product p {
            margin: 10px 0 0;
            font-size: 14px;
            color: #666;
        }


        .related-product:hover {
        transform: scale(1.05); /* Aumenta el tamaño del producto al pasar el cursor */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Añade sombra al pasar el cursor */
        }
        /* Comentarios */
        .comentarios-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .comentario-formulario, .comentarios-lista {
            margin-top: 30px;
        }

        /* Sección de Calificación */
                .calificacion {
            display: flex;
            align-items: center;
        }

        .calificacion label {
            font-size: 18px;
            color: #f39c12;
            cursor: pointer;
        }

        .estrellas .star {
            font-size: 30px;
            color: gray;
            cursor: pointer;
        }

        .estrellas .star.selected {
            color: gold;
        }

        .estrellas .star:hover,
        .estrellas .star:hover ~ .star {
            color: gold;
        }

        .comentario-texto textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        .btn-comentar {
            display: block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-comentar:hover {
            background-color: #2ecc71;
        }

        /* Área de Texto */
        .comentario-texto textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        .btn-comentar {
            display: block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-comentar:hover {
            background-color: #2ecc71;
        }

        /* Mensaje para usuarios no compradores */
        .mensaje-comprador {
            text-align: center;
            padding: 20px;
            background-color: #e74c3c;
            color: white;
            border-radius: 5px;
        }

        /* Estilo de los Comentarios de Otros Usuarios */
        .comentarios-lista {
            margin-top: 20px;
        }

        .comentario {
            display: flex;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .usuario-comentario {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .usuario-avatar img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .usuario-info {
            font-size: 16px;
        }

        .calificacion .estrella {
            color: #f39c12;
        }

        .comentario-contenido p {
            font-size: 14px;
            color: #555;
        }
        /* Comentarios */
        .modal-content {
            border-radius: 8px;
        }
        
        .btn-light {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }
        
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .char-count {
            font-size: 0.875rem;
        }
            @media (max-width: 768px) {
                .product-container {
                    grid-template-columns: 1fr;
                }
            }
    </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
 
    <div class="product-container">
    <div class="product-images">
    <div class="main-image-container">
        <?php if (!empty($imagenes)) : ?>
            <img src="data:image/jpeg;base64,<?php echo $imagenes[0]; ?>" alt="Producto Principal" class="main-image" id="mainImage">
        <?php endif; ?>
    </div>

            <div class="thumbnail-container">
                <?php if (!empty($imagenes)) : ?>
                    <?php foreach ($imagenes as $index => $imagen) : ?>
                        <img src="data:image/jpeg;base64,<?php echo $imagen; ?>" alt="Miniatura <?php echo $index + 1; ?>" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No hay imágenes disponibles para este producto.</p>
                <?php endif; ?>
            </div>
                </div>
       
        <div class="product-info">
            <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
            <?php if($producto['estado']== 'vender'):?>
            <p class="price"><?php echo htmlspecialchars($producto['precio']); ?> MXN</p>
            <?php endif; ?> 

            <p class="availability">En stock - <?php echo htmlspecialchars($producto['cantidad_disponible']); ?> unidades disponibles</p>
            
            <?php if($producto['valoracion'] != 0):?>

            <div class="rating-container">
            <div class="rating-stars" style="--rating: <?= $valoracion; ?>;">
                ★★★★★
            </div>

            <span><?= round($valoracion, 1); ?>/5 (<?= $cantidad_comentarios; ?> valoraciones)</span>
            </div>
            <?php endif; ?> 

            <p><?php echo htmlspecialchars($producto['nombre_categoria']); ?></p>
            <p>Tipo: Para <?php echo htmlspecialchars($producto['estado']); ?></p>
            <div class="description">
                <h2>Descripción</h2>
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
            </div>
            <div class="action-buttons">
            <?php if($producto['estado']== 'vender'):?>

            <form action="AgregarCarrito.php" method="POST" id="addToCartForm">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                
                <button type="submit" class="btn btn-primary" id="addToCart">Agregar al carrito</button>
            </form>
            <?php endif; ?> 

            <?php if($producto['estado']== 'vender'):?>
            <a href="#" onclick="comprarAhora()" class="btn btn-secondary">Comprar ahora</a>
            <?php endif; ?> 

            <?php if($producto['estado']== 'cotizar'):?>

                    <form method="POST" action="CrearConversacion.php">
            <input type="hidden" name="id_producto" value="<?= $id_producto ?>">
            <input type="hidden" name="id_vendedor" value="<?= $id_vendedor ?>">
            <input type="hidden" name="id_comprador" value="<?= $_SESSION['id_usuario'] ?>"> <!-- ID del comprador -->
            <button type="submit" class="btn btn-outline" id="quoteBtn">Cotizar con vendedor</button>
        </form>
        <?php endif; ?> 

            <a class="btn btn-outline" id="addToWishlist">Agregar a Lista de Deseos</a>
     
        </div>
        <?php if($producto['estado']== 'vender'):?>
          
            <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="<?php echo $max_cantidad; ?>" class="form-control" style="width: 100px; display: inline-block;">
                <?php endif; ?> 

        </div>
    </div>
    <br>        <br>        <br>        <br>



    <div class="video-container">
    <?php if (!empty($video)): ?>
        <video controls>
            <source src="<?php echo htmlspecialchars($video); ?>" type="video/mp4">
            Tu navegador no soporta el elemento de video.
        </video>
    <?php else: ?>
        <p>No hay video disponible.</p>
    <?php endif; ?>
    </div>




            <div class="related-products">
    <h2>Productos Relacionados del mismo vendedor</h2>
    <div class="related-products-grid">
        <?php if (!empty($filtered_related_products)) : ?>
            <?php foreach ($filtered_related_products as $related_product) : ?>
                <?php if ($related_product['autorizado'] == 1): ?> <!-- Condición para mostrar solo los productos autorizados -->
                <div class="related-product">
                    <a href="Producto.php?id=<?php echo $related_product['id_producto']; ?> " style="text-decoration: none;">
                        <img src="data:image/jpeg;base64,<?php echo $related_product['imagen']; ?>" class = 'relatedimg' alt="<?php echo htmlspecialchars($related_product['nombre_producto']); ?>">
                        <p><?php echo htmlspecialchars($related_product['nombre_producto']); ?></p>
                        <p class="product-price">$<?php echo number_format($related_product['precio'], 2); ?></p>
                    </a>
                </div>
                <?php endif; ?> 
            <?php endforeach; ?>
        <?php else : ?>
            <p>No hay productos relacionados para mostrar.</p>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addToListModal" tabindex="-1" aria-labelledby="addToListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addToListModalLabel">Agregar a lista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-3 cursor-pointer" onclick="showCreateListModal()">
                    <div class="me-3">
                        <div class="btn btn-light rounded-1 p-2">
                            <i class="bi bi-plus text-primary"></i>
                        </div>
                    </div>
                    <span class="text-primary">Crear nueva lista</span>
                </div>

                <form action="AgregarPrdALista.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?= $id_producto ?>"> 

                    <?php if (!empty($listas['items'])): ?>
                        <?php foreach ($listas['items'] as $lista): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                <?php if ($lista['imagen_producto']): ?>
                            <img src="data:image/jpeg;base64,<?= htmlspecialchars($lista['imagen_producto']) ?>" alt="Imagen de la lista" class="list-image">
                                <?php else: ?>
                                    <img src="Imagenes/Listas.png" alt="Imagen no disponible" class="list-image">
                                <?php endif; ?> 
                                   <div><?= htmlspecialchars($lista['nombre_lista']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($lista['descripcion']) ?></div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="listas[]" value="<?= $lista['id_lista'] ?>" id="listCheck<?= $lista['id_lista'] ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay listas disponibles.</p>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-light">Confirmar</button>
                        <button type="button" class="btn btn-link text-primary text-decoration-none" onclick="goToMyLists()">
                            Ir a mis listas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Create New List Modal -->
<div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="createListModalLabel">Crear lista de productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createListForm" method="POST">
                    <input type="hidden" id="id_producto" name="id_producto" value="ID_DEL_PRODUCTO_AQUI">
                    
                    <div class="mb-3">
                        <label for="listName" class="form-label">Nombre de la lista</label>
                        <input type="text" class="form-control" id="listName" name="listName" required maxlength="25" oninput="updateCharCount(this)">
                        <div class="char-count text-end text-muted small">0 / 25</div>
                    </div>

                    <div class="mb-3">
                        <label for="listDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="listDescription" name="listDescription" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Visibilidad</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="visibility" id="publicVisibility" value="public" required>
                            <label class="form-check-label" for="publicVisibility">Pública</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="visibility" id="privateVisibility" value="private" required>
                            <label class="form-check-label" for="privateVisibility">Privada</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Crear lista</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if ($ha_comprado > 0): ?>
    <div class="comentario-formulario">
        <h3>Agrega tu Comentario</h3>
        <form action="Agregar_Comentario.php" method="POST">
        <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>" />
            <div class="calificacion">
                <label for="calificacion">Calificación:</label>
                <div class="estrellas">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>
            </div>

            <div class="comentario-texto">
                <label for="comentario">Tu Comentario:</label>
                <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe tu experiencia..."></textarea>
            </div>

            <input type="hidden" name="calificacion" id="calificacion" value="0"> 

            <button type="submit" class="btn-comentar">Enviar Comentario</button>
        </form>
    </div>
<?php else: ?>
    <div class="mensaje-comprador">
        <p>Solo los compradores pueden dejar un comentario y calificación.</p>
    </div>
<?php endif; ?>
    <div class="comentarios-lista">
    <h3>Comentarios de Usuarios</h3>

    <?php if (!empty($comentarios)): ?>
        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
                <div class="usuario-comentario">
                    <div class="usuario-avatar">
                        <img src="<?= htmlspecialchars($comentario['avatar']) ?>" alt="Avatar Usuario" />
                    </div>
                    <div class="usuario-info">
                        <strong><?= htmlspecialchars($comentario['nombre_usuario']) ?></strong>
                        <div class="calificacion">
                            <?php
                                // Mostrar estrellas de acuerdo a la calificación
                                $calificacion = round($comentario['calificacion']);
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $calificacion ? '&#9733;' : '&#9734;';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="comentario-contenido">
                    <p><?= htmlspecialchars($comentario['comentario']) ?></p>
                    <small><?= htmlspecialchars($comentario['fecha_comentario']) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay comentarios para este producto.</p>
    <?php endif; ?>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    let stars = document.querySelectorAll('.star');
    let ratingInput = document.getElementById('calificacion');
    let submitButton = document.querySelector('.btn-comentar');
    let ratingValue = 0;
   

    
    // Añadir eventos a cada estrella
    stars.forEach(star => {
        star.addEventListener('mouseover', function () {
            let value = parseInt(star.getAttribute('data-value'));
            // Resaltar las estrellas hasta el valor de la estrella seleccionada
            stars.forEach(star => {
                if (parseInt(star.getAttribute('data-value')) <= value) {
                    star.classList.add('hover');
                } else {
                    star.classList.remove('hover');
                }
            });
        });



        star.addEventListener('mouseout', function () {
            // Quitar el resaltado al salir del mouse
            stars.forEach(star => {
                star.classList.remove('hover');
            });
        });

        star.addEventListener('click', function () {
            ratingValue = parseInt(star.getAttribute('data-value'));
            ratingInput.value = ratingValue;  // Actualizar el valor del input oculto
            submitButton.disabled = ratingValue === 0;  // Habilitar o deshabilitar el botón

            // Marcar las estrellas hasta la seleccionada
            stars.forEach(star => {
                if (parseInt(star.getAttribute('data-value')) <= ratingValue) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        });
    });
});



    document.getElementById('createListForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const idProducto = document.querySelector('input[name="id_producto"]').value; // Obtener el id_producto

    // Paso 1: Crear la lista en AgregarLista.php
    fetch('AgregarLista.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const idLista = data.id_lista; // ID de la lista creada

            // Paso 2: Agregar el producto a la lista usando AgregarPrdALista.php
            return fetch('AgregarPrdALista.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id_producto=${idProducto}&listas[]=${idLista}`
            });
        } else {
            throw new Error("Error al crear la lista: " + data.message);
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Lista creada y producto agregado exitosamente.');
            window.location.reload();
        } else {
            throw new Error("Error al agregar producto a la lista: " + data.message);
        }
    })
    .catch(error => {
        console.error(error);
       alert('Lista creada y producto agregado exitosamente.');
    });
    });
        function updateCharCount(input) {
            const maxLength = input.getAttribute('maxlength');
            const currentLength = input.value.length;
            input.nextElementSibling.textContent = `${currentLength} / ${maxLength}`;
        }
        let addToListModal;
        let createListModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            addToListModal = new bootstrap.Modal(document.getElementById('addToListModal'));
            createListModal = new bootstrap.Modal(document.getElementById('createListModal'));
            
            // Add click event listener to the "Agregar a Lista de Deseos" button
            document.getElementById('addToWishlist').addEventListener('click', function() {
                addToListModal.show();
            });
        });

        function showCreateListModal() {
            addToListModal.hide();
            createListModal.show();
        }

        function confirmAddToLists() {
            const selectedLists = [];
            if (document.getElementById('favoritesCheck').checked) {
                selectedLists.push('Favoritos');
            }
            if (document.getElementById('musicCheck').checked) {
                selectedLists.push('musica');
            }
            
            if (selectedLists.length > 0) {
                alert('Producto agregado a las listas: ' + selectedLists.join(', '));
            } else {
                alert('Por favor selecciona al menos una lista');
                return;
            }
            
            addToListModal.hide();
        }

        function goToMyLists() {
            // Redirect to lists page
            window.location.href = 'listas.php';
        }

        function updateCharCount(input) {
            const count = input.value.length;
            input.nextElementSibling.textContent = `${count} / 25`;
        }

        function createList(event) {
            event.preventDefault();
            const name = document.getElementById('listName').value;
            const description = document.getElementById('listDescription').value;
            const visibility = document.querySelector('input[name="visibility"]:checked').value;
            
            // Here you would typically send this data to your backend
            console.log({ name, description, visibility });
            
            alert(`Lista "${name}" creada exitosamente!`);
            createListModal.hide();
            addToListModal.show();
            
            // Reset the form
            event.target.reset();
        }


        const mainImage = document.getElementById('mainImage');
        const mainImageContainer = mainImage.parentElement;

        mainImageContainer.addEventListener('mousemove', (e) => {
            const { left, top, width, height } = mainImageContainer.getBoundingClientRect();
            const x = (e.clientX - left) / width;
            const y = (e.clientY - top) / height;

            mainImage.style.transformOrigin = `${x * 100}% ${y * 100}%`;
            mainImage.style.transform = 'scale(2)';
        });

        mainImageContainer.addEventListener('mouseleave', () => {
            mainImage.style.transformOrigin = 'center center';
            mainImage.style.transform = 'scale(1)';
        });

        // Cambio de imagen principal al hacer clic en las miniaturas
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                mainImage.src = thumbnail.src;
                thumbnails.forEach(t => t.classList.remove('active'));
                thumbnail.classList.add('active');
            });
        });

                function comprarAhora() {
            const cantidad = document.getElementById('cantidad').value;
            const idProducto = <?php echo $producto['id_producto']; ?>;
            window.location.href = `ResumenDeCompra.php?id=${idProducto}&cantidad=${cantidad}`;
        }
      


      
    </script>
</body>
</html>