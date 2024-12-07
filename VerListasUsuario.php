<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    $apiUsuarios = new ApiUsuarios();
    $listas = $apiUsuarios->getListasByUsuario($id_usuario);
    if (!is_array($listas)) {
        $listas = []; 
    }
    
 
    if (isset($_GET['id_lista'])) {
        $id_lista = intval($_GET['id_lista']);
        

        $productosEnLista = $apiUsuarios->getProductosEnLista($id_lista);
    
        // Aquí puedes hacer algo con los productos obtenidos, como mostrarlos en el HTML
    
    }
} else {
    echo "No se proporcionó un ID de usuario en la URL.";
}



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas de Favoritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --primary-blue: #1a73e8;
            --border-color: #e0e0e0;
            --text-gray: #5f6368;
            --background-gray: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #fff;
            color: #202124;
        }

        .breadcrumb {
            margin-bottom: 24px;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .create-list-btn {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 24px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
        }

        .list-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .list-card:hover {
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }

        .list-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
        }

        .list-content {
            padding: 16px;
        }

        .list-title {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .list-count {
            color: var(--text-gray);
            font-size: 14px;
        }

        .modal-content {
            border-radius: 8px;
            margin-top: 300px;
        }

        .char-count {
            text-align: right;
            color: var(--text-gray);
            font-size: 12px;
            margin-top: 4px;
        }

        .product-card {
            display: flex;
            padding: 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .product-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-right: 16px;

        }

        .product-price {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0;
        }

        .product-installments {
            color: var(--text-gray);
            font-size: 14px;
        }

        .shipping-info {
            color: #00a650;
            font-size: 14px;
            margin-top: 8px;
        }
                .list-title, .product-count {
            text-decoration: none;
        }

        .remove-button {
            color: var(--primary-blue);
            background: none;
            border: none;
            cursor: pointer;
            margin-top: 8px;
        }
    </style>
</head>
<body> 
    <div class="container" id="mainView">
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Listas</li>
            </ol>
        </nav>

      
        <div class="nav nav-tabs mb-4">
            <div class="nav-item">
                <a class="nav-link" href="#">Favoritos</a>
            </div>
            <div class="nav-item">
                <a class="nav-link active" href="#">Listas (<?php echo count($listas['items']); ?>)</a>
            </div>
        </div>


        <div class="row">
    <?php if (count($listas['items']) > 0): ?>
        <?php foreach ($listas['items'] as $lista): ?>
            <?php if ($lista['publica_privada'] == 1): // Solo mostrar si es pública ?>
            <div class="col-md-4 mb-4">
                    <a href="ProductosListaUsuario.php?id_lista=<?php echo $lista['id_lista']; ?>&id_usuario=<?php echo $id_usuario; ?>">
                    <div class="list-card">
                        <?php if ($lista['imagen_producto']): ?>
                            <img src="data:image/jpeg;base64,<?= htmlspecialchars($lista['imagen_producto']) ?>" alt="Imagen de la lista" class="list-image">
                        <?php else: ?>
                            <img src="Imagenes/Listas.png" alt="Imagen no disponible" class="list-image">
                        <?php endif; ?>
                        <div class="list-content">
                            <h3 class="list-title"><?php echo htmlspecialchars($lista['nombre_lista']); ?></h3>
                            <p class="product-count"><?php echo $lista['total_productos']; ?> Producto(s)</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <p>Este usuario aun no tiene listas de deseos.</p>
        </div>
    <?php endif; ?>
</div>


    <!-- Modal -->
   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let modal;

        document.addEventListener('DOMContentLoaded', function() {
            modal = new bootstrap.Modal(document.getElementById('createListModal'));
        });

        function updateCharCount(input) {
            const count = input.value.length;
            input.parentElement.querySelector('.char-count').textContent = `${count} / 25`;
        }
    </script>
</body>
</html>