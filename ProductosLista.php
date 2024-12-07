<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            position: relative; /* Add this */
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
        .delete-product-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            z-index: 2;
        }

        .product-card:hover .delete-product-btn {
            opacity: 1;
        }

        .delete-product-btn:hover {
            background-color: #dc3545;
            transform: scale(1.1);
        }

        .product-card.fade-out {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="Listas.php?id_usuario=<?php echo $id; ?>"  class="back-link">&lt; Volver a Listas</a>
    <h2><?= !empty($productosEnLista) ? htmlspecialchars($productosEnLista[0]['nombre_lista']) : "Lista sin productos" ?></h2>

    <div class="product-list-container">
        <?php if (!empty($productosEnLista)): ?>
            <?php foreach ($productosEnLista as $producto): ?>
                <div class="product-card">
                <button class="delete-product-btn" 
                        onclick="borrarProducto(<?= $id_lista ?>, <?= $producto['id_producto'] ?>, this)"
                        title="Eliminar producto">
                    <i class="bi bi-x"></i>
                </button>
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
   
</script>

<script>
function borrarProducto(idLista, idProducto, buttonElement) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto de la lista?')) {
        $.ajax({
            url: 'BorrarProductoLista.php',
            type: 'POST',
            data: {
                id_lista: idLista,
                id_producto: idProducto
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Encontrar y remover la card del producto
                        const productCard = buttonElement.closest('.product-card');
                        
                        // Agregar clase para animación de salida
                        productCard.classList.add('fade-out');
                        
                        // Remover el elemento después de la animación
                        setTimeout(() => {
                            productCard.remove();
                            
                            // Verificar si quedan productos
                            const remainingProducts = document.querySelectorAll('.product-card');
                            if (remainingProducts.length === 0) {
                                const container = document.querySelector('.product-list-container');
                                container.innerHTML = '<p>No hay productos en esta lista.</p>';
                            }
                        }, 300);
                    } else {
                        alert(data.message || 'Error al eliminar el producto');
                    }
                } catch (e) {
                    alert('Error al procesar la respuesta del servidor');
                }
            },
            error: function() {
                alert('Error al conectar con el servidor');
            }
        });
    }
}
</script>

</body>
</html>