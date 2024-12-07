<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Nueva Categoría</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
    <style>
     
        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input-container input[type="file"] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        .file-input-trigger {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #e0e0e0;
            color: #333;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .file-input-trigger:hover {
            background-color: #d0d0d0;
        }
        .file-name {
            margin-left: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .image-preview {
            max-width: 100%;
            margin-top: 1rem;
            border-radius: 4px;
            display: none;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="form-container">
        <h2>Agregar Nueva Categoría</h2>
        <form action="AgregarCtgr.php" method="POST" enctype="multipart/form-data" onsubmit="return ValidarFormulario()">
            <div class="form-group">
                <label for="nombre">Nombre de la Categoría:</label>
                <input type="text" id="nombre" name="nombre">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen de la Categoría:</label>
                <div class="file-input-container">
                    <input type="file" id="imagen" name="imagen" accept="image/*" >
                    <span class="file-input-trigger">Seleccionar Imagen</span>
                </div>
                <span class="file-name"></span>
                <img id="image-preview" class="image-preview" alt="Vista previa de la imagen">
            </div>

            <button type="submit" class="submit-btn">Crear Categoría</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('imagen');
            const fileNameSpan = document.querySelector('.file-name');
            const imagePreview = document.getElementById('image-preview');

            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    fileNameSpan.textContent = fileName;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            
        });
    </script>

    <script>
        function ValidarFormulario(){
            const nombre = document.getElementById('nombre').value;
            const descripcion = document.getElementById('descripcion').value;
            // const imagen = document.getElementById('imagen').value;
        
            let valid = true;
        
           
            // Validación de nombre
            const namePattern = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/;
            if (!namePattern.test(nombre)) {
                mostrarError('nombre', 'La categoría debe tener entre 3 y 50 caracteres');
                valid = false;
            } else {
                marcarCampoValido('nombre');
            }

            // Validación de descripción
            if (descripcion.trim() === '') {
                mostrarError('descripcion', 'La descripción no puede estar vacía');
                valid = false;
            } else {
                marcarCampoValido('descripcion');
            }        

            // Retornar el resultado de la validación
            return valid;
            
        
        
                    function mostrarError(inputId, message) {
                    const inputElement = document.getElementById(inputId);
        
                    // Comprobar si ya hay un mensaje de error
                    if (!inputElement.nextElementSibling || !inputElement.nextElementSibling.classList.contains('error-message')) {
                        const errorElement = document.createElement('div');
                        errorElement.className = 'error-message';
                        errorElement.textContent = message;
                        inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
                        
                        // Resaltar el campo con error
                        inputElement.classList.add('error');
                        inputElement.classList.remove('valid'); // Asegúrate de quitar la clase valid si existe
        
                        // Eliminar el mensaje de error después de 15 segundos
                        setTimeout(() => {
                            errorElement.remove(); // Eliminar el mensaje de error
                            inputElement.classList.remove('error'); // Limpiar la clase error
                        }, 15000);
                    } else {
                        inputElement.classList.add('error'); // Asegúrate de agregar la clase error si ya hay un mensaje
                    }
                    }
        
                    function marcarCampoValido(inputId) {
                    const inputElement = document.getElementById(inputId);
                    inputElement.classList.add('valid'); // Añadir clase valid
                    inputElement.classList.remove('error'); // Asegúrate de quitar la clase error
        
                    // Eliminar el mensaje de error si existe
                    const errorElement = inputElement.nextElementSibling;
                    if (errorElement && errorElement.classList.contains('error-message')) {
                        errorElement.remove(); // Eliminar el mensaje de error
                    }
                    }
                }
    </script>

</body>
</html>
