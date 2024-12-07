<?php
include_once 'apiUsuarios.php';
include 'navbar.php';


    $api = new ApiUsuarios();


    ob_start();
    $api->getCategorias(); 
    $CategoriasJSON = ob_get_clean();
    $Categorias = json_decode($CategoriasJSON, true);
        
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Producto - BuyHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css">
   <style>
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0000FF;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }

        .file-list {
            margin-top: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1rem;
            display: none;
        }

        .file-list.show {
            display: block;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            background-color: #f8f9fa;
            margin-bottom: 0.5rem;
            border-radius: 4px;
        }

        .file-item:last-child {
            margin-bottom: 0;
        }

        .delete-btn {
            background-color: transparent;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
        }

        .delete-btn:hover {
            color: #c82333;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }
      
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .file-input {
            margin-top: 0.5rem;
        }
        .radio-group {
            display: flex;
            gap: 1rem;
        }
        button {
            background-color: #0000FF;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0000CC;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  
    
    <div class="container">
        <h1>Vender Producto</h1>
        <form id="new-product-form" action="AgregarPrd.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product-name">Nombre del Producto:</label>
                <input type="text" id="product-name" name="nombre_producto" required>
            </div>
            <div class="form-group">
                <label for="product-description">Descripción:</label>
                <textarea id="product-description" name="product-description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                    <label>Imágenes del Producto (mínimo 3):</label>
                    <div class="file-input">
                        <input type="file" id="product-images" name="imagenes[]" accept="image/*" multiple required>
                    </div>
                    <div id="error-message" class="error-message">Por favor seleccione al menos 3 imágenes</div>
                    <div id="file-list" class="file-list"></div>
                </div>
            <div class="form-group">
                <label>Video del Producto (mínimo 1):</label>
                <div class="file-input">
                <input type="file" id="product-video" name="video[]" accept="video/*">
                </div>
            </div>

            <div class="form-group">
                    <label for="product-category">Categoría:</label>
                    <select id="product-category" name="product-category">
                        <option value="">Selecciona una categoría</option>
                        <?php if (isset($Categorias['items']) && !empty($Categorias['items'])): ?>
                            <?php foreach ($Categorias['items'] as $category): ?>
                        <option value="<?= htmlspecialchars($category['id_categoria']) ?>">
                          <?= htmlspecialchars($category['nombre_categoria']) ?>
                                 </option>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay categorías</option>
                                    <?php endif; ?>
                    </select>
                </div>
            <div class="form-group">
                <label>Tipo de Listado:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="listing-type" value="cotizar" > Para Cotizar
                    </label>
                    <label>
                        <input type="radio" name="listing-type" value="vender" > Para Vender
                    </label>
                </div>
            </div>
            
            <div class="form-group" id="price-group" style="display: none;">
                <label for="product-price">Precio:</label>
                <input type="number" id="product-price" name="precio" step="10.0" min="0">
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad_disponible" step="1." min="0" required>
            </div>
            <button type="submit">Agregar Producto</button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('product-images');
        const fileList = document.getElementById('file-list');
        const errorMessage = document.getElementById('error-message');
        let selectedFiles = [];

        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            selectedFiles = [...selectedFiles, ...files];
            updateFileList();
            validateFiles();
        });

        function updateFileList() {
            fileList.innerHTML = '';
            
            if (selectedFiles.length > 0) {
                fileList.classList.add('show');
                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <span>${file.name}</span>
                        <button type="button" class="delete-btn" data-index="${index}">
                            ×
                        </button>
                    `;
                    fileList.appendChild(fileItem);
                });
            } else {
                fileList.classList.remove('show');
            }
        }

        // Delegación de eventos para los botones de eliminar
        fileList.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                const index = parseInt(e.target.dataset.index);
                selectedFiles = selectedFiles.filter((_, i) => i !== index);
                updateFileList();
                validateFiles();
            }
        });

        // Validación y creación del campo oculto en el formulario antes de enviarlo
        const form = document.getElementById('new-product-form');
        form.addEventListener('submit', function(e) {
            if (selectedFiles.length < 3) {
                e.preventDefault(); // Evita el envío del formulario
                errorMessage.style.display = 'block'; // Muestra el mensaje de error
            } else {
                errorMessage.style.display = 'none'; // Oculta el mensaje de error
            }
        });

        const listingTypeRadios = document.querySelectorAll('input[name="listing-type"]');
        const priceGroup = document.getElementById('price-group');

        listingTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'vender') {
                    priceGroup.style.display = 'block';
                    document.getElementById('product-price').required = true;
                } else {
                    priceGroup.style.display = 'none';
                    document.getElementById('product-price').required = false;
                }
            });
        });
    });
</script>

<script>
    
</script>


</body>
</html>