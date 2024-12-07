<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];
}
// Suponiendo que tienes el id_lista en la URL (por ejemplo, ProductosLista.php?id_lista=1)
$id_lista = isset($_GET['id_lista']) ? intval($_GET['id_lista']) : 0;

$apiUsuarios = new ApiUsuarios();
$productosEnLista = $apiUsuarios->getProductosEnLista($id_lista);

if (!is_array($productosEnLista)) {
    $productosEnLista = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos en Lista</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/5eN6ZZhrx5rZdVxn59i+N+WghMyWfbCy10x43A" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .product-list-container {
            margin-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        .product-card {
            width: 200px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
        .product-image {
            width: 100%;
            height: 150px;
            object-fit: contain;
        }
        .product-info {
            padding: 10px;
        }
        .product-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .product-price {
            font-size: 1rem;
            color: #888;
            text-align: center;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="VerListasUsuario.php?id_usuario=<?php echo $id_usuario; ?>"  class="back-link">&lt; Volver a Listas</a>
    <h2><?= !empty($productosEnLista) ? htmlspecialchars($productosEnLista[0]['nombre_lista']) : "Lista sin productos" ?></h2>

    <div class="product-list-container">
        <?php if (!empty($productosEnLista)): ?>
            <?php foreach ($productosEnLista as $producto): ?>
                <div class="product-card">
                    <img src="data:image/jpeg;base64,<?= htmlspecialchars($producto['imagen_producto']) ?>" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" class="product-image">
                    <div class="product-info">
                        <div class="product-title"><?= htmlspecialchars($producto['nombre_producto']) ?></div>
                        <div class="product-price">$<?= number_format($producto['precio'], 2) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos en esta lista.</p>
        <?php endif; ?>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>