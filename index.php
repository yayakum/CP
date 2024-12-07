<?php
include_once 'apiUsuarios.php';
include 'navbar.php';
$api = new ApiUsuarios();

    ob_start();
    $api->getCategorias(); 
    $CategoriasJSON = ob_get_clean();
    $Categorias = json_decode($CategoriasJSON, true);


    ob_start();
     $api->getAllProductos(); 
     $productosJSON = ob_get_clean();
     $productos = json_decode($productosJSON, true);

   // echo $productosJSON;
    

?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;

        
        }
        .category-link {
            text-decoration: none; /* Elimina el subrayado */
            color: inherit; /* Opcional: hereda el color del elemento padre */
        }

        .category-link:hover {
            color: inherit; /* Asegura que el color no cambie al pasar el cursor */
            text-decoration: none; /* Evita subrayado en hover */
        }
        .hero-section {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .custom-carousel {
            max-width: 100%; /* El carrusel ocupa todo el ancho disponible */
            width: 100%; /* Asegura que el carrusel sea responsivo */
            max-height: 400px; /* Altura máxima del carrusel para darle forma de banner */
            margin: 10px auto; /* Centra el carrusel horizontalmente */
            margin-top: -200px;
            border: 1px solid #ddd; /* (Opcional) Añadir borde para mayor claridad */
            border-radius: 10px; /* (Opcional) Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* (Opcional) Añade una sombra para darle profundidad */
            overflow: hidden; /* Oculta cualquier contenido que exceda el contenedor */
        }
        
        .carousel-inner img {
            width: 100%; 
            height: auto; 
            object-fit: cover; /* Cubre todo el contenedor sin distorsionar la imagen */
        }
        .featured-categories {
            padding: 3rem 0;
            background-color: white;
        }
        .category-card {
            text-align: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-card img {
            width: 100%;
            max-width: 150px;
            height: auto;
            margin-bottom: 1rem;
        }
        .special-offers {
            background-color: #f8f9fa;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        .special-offers::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            transform: skewY(-6deg);
            transform-origin: top left;
            z-index: 0;
        }
        .special-offers .container {
            position: relative;
            z-index: 1;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .offer-card {
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        .offer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .offer-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .offer-card:hover img {
            transform: scale(1.05);
        }
        .offer-card .card-body {
            padding: 1.5rem;
        }
        .offer-card .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .offer-card .card-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #e44d26;
            margin-bottom: 1rem;
        }
        .btn-comprar {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-comprar:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .featured-products {
            background-color: #ffffff;
            padding: 4rem 0;
        }
        .featured-card {
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
        }
        .featured-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .featured-card .card-img-top {
            border-radius: 0;
            height: 250px;
            object-fit: cover;
        }
        .featured-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ffc107;
            color: #000;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 20px;
        }
         /* Estilos para Últimas Novedades */
         .latest-arrivals {
            background-color: #f1f3f5;
            padding: 4rem 0;
        }
        .arrival-card {
            background-color: #ffffff;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .arrival-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .arrival-card .card-img-wrapper {
            overflow: hidden;
            position: relative;
            object-fit: contain;
        }
        .arrival-card .card-img-top {
            height: 200px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .arrival-card:hover .card-img-top {
            transform: scale(1.1);
        }
        .new-label {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #143EE0;
            color: white;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 20px;
        }

        /* Estilos comunes */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .btn-comprar {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-comprar:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .footer {
            background-color: #343a40;
            color: rgb(0, 0, 0);
            padding: 2rem 0;
        }
        .Siguenos{

            margin-left: 10px;
        }
</style>
  </head>

<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<!-- Main Carousel -->
<div id="carouselExample" class="carousel slide custom-carousel mt-4">
  <div class="carousel-inner">
      <div class="carousel-item active">
          <img src="Imagenes/banner1.jpg" class="d-block w-10" alt="Oferta especial">
      </div>
      <div class="carousel-item">
          <img src="Imagenes/banner2.jpg" class="d-block w-100" alt="Nuevos productos">
      </div>
      <div class="carousel-item">
          <img src="Imagenes/banner3.jpg" class="d-block w-100" alt="Descuentos exclusivos">
      </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
  </button>
</div>

<!-- Featured Categories -->
<section class="featured-categories">
  <div class="container">
      <h2 class="text-center mb-4">Categorías Populares</h2>
      <div class="row">
          <?php if (!empty($Categorias['items'])): ?>
              <?php foreach ($Categorias['items'] as $categoria): ?>
                  <div class="col-6 col-md-3">
                      <a href="ResultadosCategorias.php?id_categoria=<?php echo $categoria['id_categoria']; ?>" class="category-link">
                          <div class="category-card">
                              <!-- Imagen de la categoría -->
                              <img src="data:image/jpeg;base64,<?php echo $categoria['imagen']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>">
                              <!-- Nombre de la categoría -->
                              <h3><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></h3>
                          </div>
                      </a>
                  </div>
              <?php endforeach; ?>
          <?php else: ?>
              <p class="text-center">No hay categorías disponibles.</p>
          <?php endif; ?>
      </div>
  </div>
</section>

<section class="special-offers">
    <div class="container">
        <h2 class="section-title">Mejor Calificado</h2>
        <div class="row">
            <?php if (!empty($productos['items'])): ?>
                <?php foreach ($productos['items'] as $producto): ?>
                    <?php if ($producto['autorizado'] == 1 && $producto['valoracion'] >= 4): ?> <!-- Filtrar por productos autorizados y calificación > 4 -->
                        <div class="col-md-4">
                            <div class="offer-card">
                                <div class="position-relative">
                                    <img src="data:image/jpeg;base64,<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                    <div class="mb-3">
                                    <?php
                                        $valoracion = round($producto['valoracion']); // Redondea la valoración
                                        $estrellas = str_repeat('★', $valoracion) . str_repeat('☆', 5 - $valoracion); // Estrellas llenas y vacías
                                    ?>
                                    <span class="rating-stars"><?php echo $estrellas; ?></span>
                                    <span class="text-muted">(<?php echo $producto['comentarios']; ?> comentarios)</span>
                
                                    </div>
                                    <?php if($producto['estado'] == 'vender'): ?>
                                        <p class="card-text">$<?php echo htmlspecialchars($producto['precio']); ?> MXN</p>
                                        
                                    <?php endif; ?>


                                    <input type="hidden" value="<?php echo $producto['id_producto']; ?>">
                                    <a href="Producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-comprar">
                                        <i class="fas fa-shopping-cart"></i> Ver Producto
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay productos disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="featured-products">
    <div class="container">
        <h2 class="section-title">Productos para Cotizar</h2>
        <div class="row">
            <?php if (!empty($productos['items'])): ?>
                <?php foreach (($productos['items']) as $producto): ?>
                    <?php if ($producto['autorizado'] == 1 && $producto['estado'] === 'cotizar'): ?> <!-- Filtro adicional por estado -->
                    <div class="col-md-3 mb-4">
                        <div class="card featured-card">
                            <div class="position-relative">
                                <img src="data:image/jpeg;base64,<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="card-img-top">
                                <span class="featured-badge">Destacado</span>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                <a href="Producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-outline-primary">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay productos destacados disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="latest-arrivals">
    <div class="container">
        <h2 class="section-title">Últimas Novedades</h2>
        <div class="row">
            <?php if (!empty($productos['items'])): ?>
                <?php foreach (($productos['items']) as $producto): ?>
                    <?php if ($producto['autorizado'] == 1): ?> 
                    <div class="col-md-4 mb-4">
                        <div class="card arrival-card">
                            <div class="card-img-wrapper">
                                <img src="data:image/jpeg;base64,<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="card-img-top">
                                <span class="new-label">Nuevo</span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                                <p class="card-text">$<?php echo htmlspecialchars($producto['precio']); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="Producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-comprar">Comprar</a>
                                    <button class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay nuevos productos disponibles en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Footer -->
<footer class="footer">
  <div class="container">
      <div class="row">
          <div class="col-md-4">
              <h5>Acerca de BuyHub</h5>
              <p>Tu destino de compras en línea para encontrar los mejores productos a precios increíbles.</p>
          </div>
          <div class="col-md-4">
              <h5>Enlaces Rápidos</h5>
              <ul class="list-unstyled">
                  <li><a href="#" class="text-white">Sobre Nosotros</a></li>
                  <li><a href="#" class="text-white">Contacto</a></li>
                  <li><a href="#" class="text-white">Términos y Condiciones</a></li>
                  <li><a href="#" class="text-white">Política de Privacidad</a></li>
              </ul>
          </div>
          <div class="col-md-4">
              <h5>Síguenos</h5>
              <div class="Siguenos ">
                  <a href="#"  class="bi bi-facebook"></i></a>
                  <a href="# " class="bi bi-twitter"></i></a>
                  <a href="#" ><i class="bi bi-instagram"></i></a>
              </div>
          </div>
      </div>
      <hr class="mt-4">
      <div class="text-center">
          <p>&copy; 2024 BuyHub. Todos los derechos reservados.</p>
      </div>
  </div>
</footer>

</body>
</html>