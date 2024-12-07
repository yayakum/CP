<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

$api = new ApiUsuarios();

$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : $_SESSION['id_usuario'];

$id_categoria = isset($_GET['product-category']) ? $_GET['product-category'] : null;

ob_start();
$api->getCategorias();
$CategoriasJSON = ob_get_clean();
$Categorias = json_decode($CategoriasJSON, true);

$productos = $api->getProductosByVendedorYCategoria($id_usuario, $id_categoria);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos en Venta - BuyHub</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   
    <div class="container">
    <form method="GET" action="ProductosVendedor.php">
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="filtroCategoria">Filtrar por Categoría:</label>
            <select id="product-category" name="product-category">
                <option value="">Todas las categorias</option>
                <?php if (isset($Categorias['items']) && !empty($Categorias['items'])): ?>
                    <?php foreach ($Categorias['items'] as $category): ?>
                        <option value="<?= htmlspecialchars($category['id_categoria']) ?>" 
                            <?= isset($_GET['product-category']) && $_GET['product-category'] == $category['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['nombre_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No hay categorías</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Ver</button>
        </div>
        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">

    </div>
</form>
<div class="products-grid">
    <?php if (!empty($productos['items'])): ?>
        <?php 
        $productos_autorizados = array_filter($productos['items'], function($producto) {
            return $producto['autorizado'] == 1; // Filtrar solo productos autorizados
        });
        ?>
        
        <?php if (!empty($productos_autorizados)): ?>
            <?php foreach ($productos_autorizados as $producto): ?>
                <div class="product-card">
                    <?php $imagen_base64 = $producto['imagen']; ?> <!-- Asignación de imagen base64 -->
                    <img src="data:image/jpeg;base64,<?php echo $imagen_base64; ?>" class="product-image">
                    <div class="product-info">
                        <h2 class="product-name"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
                        <p class="product-description">
                            <?php 
                            $descripcion = htmlspecialchars($producto['descripcion']);
                            echo strlen($descripcion) > 100 ? substr($descripcion, 0, 100) . "..." : $descripcion; 
                            ?>
                        </p>
                        <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                        <p class="product-stock">En stock: <?php echo $producto['cantidad_disponible']; ?> unidades</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos autorizados disponibles.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No tienes productos en venta.</p>
    <?php endif; ?>
</div>

</body>
</html>
