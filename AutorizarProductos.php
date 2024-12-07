<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

$api = new ApiUsuarios();

ob_start();
$api->getAllAdmin(); 
$productosJson = ob_get_clean();
$productos = json_decode($productosJson, true);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorización de Productos</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #e74c3c;
            --background-color: #ecf0f1;
            --text-color: #2c3e50;
            --border-color: #bdc3c7;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            margin: 0;
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        header {

            background-color: var(--primary-color);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .product-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item:nth-child(even) {
            background-color: #f8f9fa;
        }

        .product-item:hover {
            background-color: #e9ecef;
        }

        .product-checkbox {
            margin-right: 15px;
            width: 18px;
            height: 18px;
            border: 2px solid var(--secondary-color);
            border-radius: 3px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            position: relative;
        }

        .product-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--secondary-color);
            font-size: 14px;
        }

        .product-name {
            flex-grow: 1;
            font-size: 16px;
        }

        .authorize-section {
            background-color: #f1f3f5;
            padding: 20px;
            text-align: right;
        }

        .authorize-btn {
            background-color: var(--accent-color);
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .authorize-btn:hover {
            background-color: #c0392b;
        }

        .authorize-btn:active {
            transform: scale(0.98);
        }

        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }

            .product-item {
                padding: 12px 15px;
            }

            .product-name {
                font-size: 14px;
            }

            .authorize-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Autorización de Productos</h1>
    </header>
    <ul class="product-list">
        <?php if (isset($productos['items']) && count($productos['items']) > 0): ?>
            <?php foreach ($productos['items'] as $producto): ?>
                <li class="product-item">
                    <input type="checkbox" id="product<?php echo $producto['id_producto']; ?>" class="product-checkbox" value="<?php echo $producto['id_producto']; ?>" aria-label="Seleccionar Producto <?php echo $producto['id_producto']; ?>">
                    <label for="product<?php echo $producto['id_producto']; ?>" class="product-name">
                        <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                    </label>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="product-item">No hay productos pendientes de autorización.</li>
        <?php endif; ?>
    </ul>
    <div class="authorize-section">
        <button class="authorize-btn" id="authorizeBtn">Autorizar Seleccionados</button>
    </div>
</div>

<script>
document.getElementById('authorizeBtn').addEventListener('click', function() {
    let selectedProducts = [];
    document.querySelectorAll('.product-checkbox:checked').forEach(function(checkbox) {
        selectedProducts.push(checkbox.value); 
    });

    if (selectedProducts.length > 0) {
        // Enviar los IDs seleccionados al servidor para actualizarlos en la base de datos
        let formData = new FormData();
        formData.append('producto_ids', JSON.stringify(selectedProducts)); // Pasar los IDs seleccionados como JSON

        fetch('autorizarPrds.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Productos autorizados correctamente:)');

                // Eliminar los productos autorizados de la lista
                selectedProducts.forEach(function(id) {
                    let checkbox = document.getElementById('product' + id);
                    let listItem = checkbox.closest('.product-item'); // Encuentra el <li> del producto
                    listItem.remove(); // Elimina el <li> del DOM
                });
            } else {
                alert('Hubo un error al autorizar los productos');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al procesar la solicitud');
        });
    } else {
        alert('Por favor, selecciona al menos un producto.');
    }
});
</script>
</body>
</html>