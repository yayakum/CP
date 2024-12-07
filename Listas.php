<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

$id_usuario = $_SESSION['id_usuario']; 

$apiUsuarios = new ApiUsuarios();
$listas = $apiUsuarios->getListasByUsuario($id_usuario);

if (!is_array($listas)) {
    $listas = []; // Asegúrate de que sea un arreglo vacío si no es un arreglo
}

// Verifica si el id_lista está presente en la URL
if (isset($_GET['id_lista'])) {
    $id_lista = intval($_GET['id_lista']);
    
    // Llama a la función getProductosEnLista para obtener los productos de la lista
    $productosEnLista = $apiUsuarios->getProductosEnLista($id_lista);

    // Aquí puedes hacer algo con los productos obtenidos, como mostrarlos en el HTML

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
            background-color: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .remove-button:hover {
            background-color: #dc3545;
            color: white;
        }
        .edit-button {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .edit-button:hover {
            background-color: #1557b0;
        }
    </style>
</head>
<body> 
    <div class="container" id="mainView">
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="PerfilPublico.php">Perfil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listas</li>
            </ol>
        </nav>

        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1>Listas</h1>
            <button class="btn create-list-btn" onclick="showModal()">
                + Crear lista
            </button>
        </header>

        <div class="nav nav-tabs mb-4">
            <div class="nav-item">
                <a class="nav-link active" href="#">Listas (<?php echo count($listas['items']); ?>)</a>
            </div>
        </div>

        <div class="row">
        <?php if (count($listas['items']) > 0): ?>
        <?php foreach ($listas['items'] as $lista): ?>
            <div class="col-md-4 mb-4">
            <a href="ProductosLista.php?id_lista=<?php echo $lista['id_lista']; ?>&id_usuario=<?php echo $id_usuario; ?>">
            <div class="list-card">
                        <?php if ($lista['imagen_producto']): ?>
                            <img src="data:image/jpeg;base64,<?= htmlspecialchars($lista['imagen_producto']) ?>" alt="Imagen de la lista" class="list-image">
                        <?php else: ?>
                            <img src="Imagenes/Listas.png" alt="Imagen no disponible" class="list-image">
                        <?php endif; ?>
                        <div class="list-content">
                            <h3 class="list-title"><?php echo htmlspecialchars($lista['nombre_lista']); ?></h3>
                            <p class="product-count"><?php echo $lista['total_productos']; ?> Producto(s)</p>
                            <div class="d-flex gap-2">
                                  <button type="button" class="btn btn-primary btn-sm edit-button" 
                                    onclick="event.preventDefault(); showEditModal(<?php echo $lista['id_lista']; ?>, 
                                    '<?php echo htmlspecialchars(addslashes($lista['nombre_lista'])); ?>', 
                                    '<?php echo htmlspecialchars(addslashes($lista['descripcion'])); ?>', 
                                    <?php echo (int) $lista['publica_privada']; ?>)">
                                    Editar lista
                                </button>
                                <button type="button" class="btn btn-danger btn-sm remove-button" 
                                    onclick="borrarLista(<?php echo $lista['id_lista']; ?>, this)">
                                    Borrar lista
                                </button>
                            </div>

                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <p>No tienes listas de productos.</p>
        </div>
    <?php endif; ?>
</div>


    <!-- Modal -->
    <div class="modal fade" id="createListModal" tabindex="-1" aria-labelledby="createListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createListModalLabel">Crear lista de productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createListForm">
                    <div class="mb-3">
                        <label for="listName" class="form-label">Nombre de la lista</label>
                        <input type="text" class="form-control" id="listName" name="listName" required maxlength="25" oninput="updateCharCount(this)">
                        <div class="char-count">0 / 25</div>
                    </div>

                    <div class="mb-3">
                        <label for="listDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="listDescription" name="listDescription" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Visibilidad</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="publicVisibility" value="1" required>
                            <label class="form-check-label" for="publicVisibility">Pública</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="privateVisibility" value="0" required>
                            <label class="form-check-label" for="privateVisibility">Privada</label>
                        </div>
                    </div>

                    <button type="submit" class="btn create-list-btn">Crear lista</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- editar -->

<div class="modal fade" id="editListModal" tabindex="-1" aria-labelledby="editListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editListModalLabel">Editar lista de productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editListForm">
                    <input type="hidden" id="editListId" name="id_lista">
                    <div class="mb-3">
                        <label for="editListName" class="form-label">Nombre de la lista</label>
                        <input type="text" class="form-control" id="editListName" name="listName" required maxlength="25" oninput="updateCharCount(this)">
                        <div class="char-count">0 / 25</div>
                    </div>

                    <div class="mb-3">
                        <label for="editListDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editListDescription" name="listDescription" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Visibilidad</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="editPublicVisibility" value="1" required>
                            <label class="form-check-label" for="editPublicVisibility">Pública</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="editPrivateVisibility" value="0" required>
                            <label class="form-check-label" for="editPrivateVisibility">Privada</label>
                        </div>
                    </div>

                    <button type="submit" class="btn create-list-btn">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let modal;
    let editModal;
    document.addEventListener('DOMContentLoaded', function() {
        modal = new bootstrap.Modal(document.getElementById('createListModal'));
        editModal = new bootstrap.Modal(document.getElementById('editListModal'));
    });

    function showModal() {
        modal.show();
    }

    function hideModal() {
        modal.hide();
    }

    function updateCharCount(input) {
        const count = input.value.length;
        input.parentElement.querySelector('.char-count').textContent = `${count} / 25`;
    }
    function borrarLista(idLista, buttonElement) {
    if (confirm('¿Estás seguro de que deseas borrar esta lista?')) {
        $.ajax({
            url: 'BorrarLista.php',
            type: 'POST',
            data: {
                id_lista: idLista
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        // Encontrar y remover la card completa
                        const listCard = buttonElement.closest('.col-md-4');
                        listCard.remove();
                        
                        // Actualizar el contador de listas en la pestaña
                        const listCountElement = document.querySelector('.nav-link.active');
                        const currentCount = parseInt(listCountElement.textContent.match(/\d+/)[0]) - 1;
                        listCountElement.textContent = `Listas (${currentCount})`;
                        
                        // Si no quedan listas, mostrar mensaje
                        if (currentCount === 0) {
                            const row = document.querySelector('.row');
                            row.innerHTML = '<div class="col-12"><p>No tienes listas de productos.</p></div>';
                        }
                    } else {
                        alert(data.message || 'Error al borrar la lista');
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
function showEditModal(idLista, nombreLista, descripcion, publicaPrivada) {
    document.getElementById('editListId').value = idLista;
    document.getElementById('editListName').value = nombreLista;
    document.getElementById('editListDescription').value = descripcion;
    
    if (publicaPrivada === 1) {
        document.getElementById('editPublicVisibility').checked = true;
    } else {
        document.getElementById('editPrivateVisibility').checked = true;
    }
    
    let editModal = new bootstrap.Modal(document.getElementById('editListModal'));
    editModal.show();
}

function hideEditModal() {
    editModal.hide();
}
document.getElementById('editListForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar recarga de la página

    // Obtener los datos del formulario
    const formData = new FormData(this);

    // Enviar los datos con Fetch
    fetch('EditarLista.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // Notificar éxito
            location.reload();   // Recargar página para reflejar cambios
        } else {
            alert(data.message); // Notificar error
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
    });
});
    // Manejo de formulario con AJAX
    $("#createListForm").on("submit", function(event) {
        event.preventDefault();  // Evitar la recarga de la página

        // Obtener los datos del formulario
        var formData = new FormData(this);

        $.ajax({
            url: "AgregarLista.php",  // El archivo PHP que manejará la creación de la lista
            type: "POST",
            data: formData,
            processData: false,  // No procesar los datos del formulario
            contentType: false,  // No establecer tipo de contenido
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Cerrar el modal si la lista se creó exitosamente
                    hideModal();

                    // Opcional: actualizar la página o redirigir según sea necesario
                    window.location.href = "Listas.php?id_lista=" + data.id_lista;
                } else {
                    alert(data.message);  // Mostrar un mensaje de error si ocurrió algún problema
                }
            },
            error: function() {
                alert("Ocurrió un error. Inténtalo nuevamente.");
            }
        });
    });
</script>
</body>
</html>