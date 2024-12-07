<?php
require_once 'apiUsuarios.php';
include 'navbar.php';

if (isset($_GET['query'])) {
    $termino_busqueda = $_GET['query'];

    $ordenar = $_GET['ordenar'] ?? 'precio_asc';

    // Instancia la clase ApiUsuarios
    $api = new ApiUsuarios();
    
    $resultados = $api->buscarPorNombre($termino_busqueda, $ordenar);
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
        
    <!-- Vincula Bootstrap CSS si usas CDN -->
    
    <style>
        
        .product-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .rating-stars {
            color: #ffd700;
        }
        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9em;
        }
        .discount-badge {
            background-color: #39b54a;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
                .product-card .card-img-top {
            max-width: 100%;
            height: 200px;
            object-fit: contain;
        }
                
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-12 col-md-3 p-3">
            <!-- Sidebar content as is -->
            <h5>Filtros</h5>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="llegaManana">
                        <label class="form-check-label" for="llegaManana">Llega mañana</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="envioGratis">
                        <label class="form-check-label" for="envioGratis">Envío gratis</label>
                    </div>
                </div>
                <hr>
                <h6>Categorías</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-decoration-none text-dark">Consolas y Videojuegos (47,665)</a></li>
                    <li><a href="#" class="text-decoration-none text-dark">Electrónica, Audio y Video (86,683)</a></li>
                    <li><a href="#" class="text-decoration-none text-dark">Juegos y Juguetes (41,341)</a></li>
                </ul> 
        </div>

        <!-- Main Content -->
        <div class="col-12 col-md-9 p-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Resultados de búsqueda para: "<?php echo htmlspecialchars($termino_busqueda); ?>"</h1>
                <select class="form-select" style="width: auto;">
                    <option>Más relevantes</option>
                    <option>Menor precio</option>
                    <option>Mayor precio</option>
                </select>
            </div>

            <div class="row g-4">
                <?php if (!empty($resultados['productos'])): ?>
                    <?php foreach ($resultados['productos'] as $producto): ?>
                        <?php if ($producto['autorizado'] == 1): ?> 

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card product-card">
                                <img src="data:image/jpeg;base64,<?php echo $producto['imagen']; ?>" class="card-img-top p-4" alt="<?php echo $producto['nombre_producto']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $producto['nombre_producto']; ?></h5>
                                    <div class="mb-2">
                                    <?php
                                        $valoracion = round($producto['valoracion']);
                                        $estrellas = str_repeat('★', $valoracion) . str_repeat('☆', 5 - $valoracion);
                                    ?>
                                    <span class="rating-stars"><?php echo $estrellas; ?></span>
                                    <span class="text-muted">(<?php echo $producto['comentarios']; ?> comentarios)</span>
                
                                    </div>
                                    <div class="mb-2">
                                        <span class="h4">$<?php echo number_format($producto['precio'], 2); ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span>Por <?php echo $producto['vendedor']; ?></span>
                                        <i class="bi bi-patch-check-fill text-primary ms-2"></i>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-success">
                                            <i class="bi bi-truck"></i> Llega gratis mañana
                                        </span>
                                    </div>
                                    <a href="Producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-primary mt-3">Ver Producto</a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?> 
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php if (empty($resultados['productos']) && !empty($resultados['usuarios'])): ?>
                <?php foreach ($resultados['usuarios'] as $usuario): ?>
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <img src="<?php echo $usuario['avatar']; ?>"
                                        class="rounded-circle"
                                        alt="Avatar de <?php echo $usuario['nombre_usuario']; ?>"
                                        style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #ddd;">
                                </div>
                                <div>
                                    <h5 class="card-title mb-1"><?php echo $usuario['nombre_usuario']; ?></h5>
                                    <p class="card-text text-muted mb-2"><?php echo $usuario['email']; ?></p>
                                    <a href="VerPerfil.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-primary">Ver Perfil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

                <?php if (empty($resultados['productos']) && empty($resultados['usuarios'])): ?>
                    <p>No se encontraron productos ni usuarios.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


</body>
</html>
</html>