<?php
include 'navbar.php';
include_once 'apiUsuarios.php';



$apiUsuarios = new ApiUsuarios();
$id_usuario = $_SESSION['id_usuario']; // ID del usuario actual
$es_vendedor = isset($_GET['es_vendedor']) && $_GET['es_vendedor'] == 1;

$conversaciones = $apiUsuarios->obtenerConversaciones($id_usuario, $es_vendedor);


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .cotizacion-message {
    background-color: #f0f8ff;
    border: 1px solid #a0c6e5;
    border-radius: 8px;
}

.cotizacion-header {
    background-color: #a0c6e5;
    color: white;
    padding: 5px 10px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    font-weight: bold;
}

.cotizacion-body {
    padding: 10px;
}
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            
            color: #333;
        }

        .container {
            padding:20px;
            display: flex;
            width: 100%;
            height: 100%;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 300px;
            background-color: #f7f7f7;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
        }

        .chat-list {
            list-style-type: none;
        }

        .chat-item {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }

        .chat-item:hover {
            background-color: #e8e8e8;
        }

        .chat-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        .chat-item-info {
            display: flex;
            flex-direction: column;
        }

        .chat-item-name {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .chat-item-preview {
            font-size: 0.9em;
            color: #777;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .chat-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background-color: #212529;
            
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 1.0em;
            font-weight: 500;
            width: 1000px; /* Ancho fijo en píxeles */
            height:90px;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            scroll-behavior: ; /* Suaviza el scroll al final */
            height: 500px; 
        }

        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 70%;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.sent {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }

        .message.received {
            background-color: #e9e9e9;
            align-self: flex-start;
        }

        .chat-input {
            display: flex;
            padding: 20px;
            background-color: #f7f7f7;
            border-top: 1px solid #e0e0e0;
        }

        .chat-input input {
            flex-grow: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s;
        }

        .chat-input input:focus {
            border-color: #007bff;
        }

        .chat-input button {
            padding: 15px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 30px;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-input button:hover {
            background-color: #0056b3;
        }

        .chat-input button i {
            margin-left: 5px;
        }

        .message-wrapper {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message-content {
            flex-grow: 1;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.8em;
        }

        .message.sent .message-wrapper {
            flex-direction: row-reverse;
        }

        .message.sent .message-avatar {
            margin-right: 0;
            margin-left: 10px;
        }
        .btn-cotizar {
            margin-left: 10px;
            background-color: #212529;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 0.9em;
            cursor: pointer;
        }
        .btn-cotizar:hover {
            background-color: #212529;
        }
        /* Estilo para chat activo */
        .chat-item.active {
            background-color: #e8e8e8;
        }
        /* Estilos para la modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
        }

        .modal-close {
            background: #f44336;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                max-height: 30vh;
            }

            .chat-main {
                height: 70vh;
            }
        }
    </style>
</head>
<body>


<div class="container"> 
    <div class="sidebar">
        <ul class="chat-list">
        <?php foreach ($conversaciones as $conv): ?>
    <li class="chat-item" 
        data-chat="<?= $conv['id_conversacion']; ?>"
        data-producto="<?= $conv['id_producto']; ?>"
        <?= isset($conv['id_comprador']) ? 'data-comprador="' . $conv['id_comprador'] . '"' : ''; ?> >
        
        <img src="<?= $conv['avatar_otro_usuario']; ?>" alt="Usuario">
        <div class="chat-item-info">
            <span class="chat-item-name"><?= $conv['otro_usuario']; ?></span>
            <span class="chat-item-preview"><?= $conv['nombre_producto']; ?></span>
            <span class="chat-item-preview"><?= $conv['id_producto']; ?></span>
        </div>
    </li>
<?php endforeach; ?>
        </ul>
    </div>
    <div class="chat-main">
        <div class="chat-header">
            <h2>Cotización: Producto</h2>
        </div>
        <div class="chat-messages">
            <!-- Mensajes se mostrarán aquí -->
        </div>
        <div class="chat-input">
                       
            <input type="text" placeholder="Escribe un mensaje..." id="messageInput">
            <button id="sendButton">Enviar <i class="material-icons">send</i></button>
            <?php if ($es_vendedor == 1): ?>
                            <button class="btn btn-primary btn-cotizar" data-producto="<?= $conv['id_producto']; ?>">
                                Cotizar
                            </button>
                        <?php endif; ?>
        </div>
    </div>
</div>
<!-- Modal de Cotización -->
<div id="modalCotizar" class="modal">
    <div class="modal-content">
        <h4>Cotización para el Producto</h4>
        <form id="cotizarForm">
            <div class="form-group">
                <label for="precioCotizacion">Precio Unitario</label>
                <input type="number" step="0.01" id="precioCotizacion" name="precioCotizacion" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" min="1" required>
            </div>
            <div class="form-group">
                <label for="comentariosCotizacion">Comentarios</label>
                <textarea id="comentariosCotizacion" name="comentariosCotizacion" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enviar Cotización</button>
                <button type="button" class="btn modal-close">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const chatItems = document.querySelectorAll('.chat-item');
    const chatHeader = document.querySelector('.chat-header h2');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatMessages = document.querySelector('.chat-messages');
    const modal = document.getElementById('modalCotizar');

    let currentConversationId = null;
    const mensajesCargados = new Set();

    // Función para cambiar de conversación
// Modificar la función que ya existe
chatItems.forEach(item => {
    item.addEventListener('click', () => {
        chatItems.forEach(i => i.style.backgroundColor = '');
        item.style.backgroundColor = '#e0e0e0';

        const producto = item.querySelector('.chat-item-preview').textContent;
        const conversationId = item.getAttribute('data-chat');
        const idProducto = item.getAttribute('data-producto');
        const idComprador = item.getAttribute('data-comprador');
        currentConversationId = conversationId;

        // Actualizar la URL y reemplazar el id_producto
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('id_producto', idProducto);
        window.history.replaceState({}, '', currentUrl);

        mensajesCargados.clear();
        chatHeader.textContent = `Cotización: ${producto}`;

        const btnCotizar = document.querySelector('.btn-cotizar');
        if (btnCotizar) {
            btnCotizar.setAttribute('data-producto', idProducto);
            btnCotizar.setAttribute('data-comprador', idComprador);
        }

        // Cargar mensajes
        cargarMensajes(conversationId);
    });
});

    // Función para cargar mensajes
    function cargarMensajes(conversationId) {
        fetch(`obtenerMensajes.php?id_conversacion=${conversationId}`)
            .then(response => response.json())
            .then(data => {
                chatMessages.innerHTML = "";
                data.forEach(msg => agregarMensaje(msg));
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => console.error('Error al cargar mensajes:', error));
    }

    // Manejo del botón de cotizar
    const btnCotizar = document.querySelector('.btn-cotizar');
    if (btnCotizar) {
        btnCotizar.addEventListener('click', function() {
            const idProducto = this.getAttribute('data-producto');
            const idComprador = this.getAttribute('data-comprador');
            
            // Actualizar atributos del modal
            modal.setAttribute('data-producto', idProducto);
            modal.setAttribute('data-comprador', idComprador);
            
            // Actualizar título del modal
            const modalTitle = modal.querySelector('h4');
            modalTitle.textContent = `Cotización para el Producto: ${idProducto}`;
            
            // Mostrar modal
            modal.style.display = 'block';
        });
    }

    // Cerrar modal
    const closeModalButtons = document.querySelectorAll('.modal-close');
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = 'none';
            document.getElementById('cotizarForm').reset();
        });
    });

    // Manejo del formulario de cotización
    document.getElementById('cotizarForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const idProducto = modal.getAttribute('data-producto');
        const idComprador = modal.getAttribute('data-comprador');
        const precio = document.getElementById('precioCotizacion').value;
        const cantidad = document.getElementById('cantidad').value;
        const comentarios = document.getElementById('comentariosCotizacion').value;
        
        fetch('insertarCotizacion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id_producto=${idProducto}&id_comprador=${idComprador}&precio=${precio}&cantidad=${cantidad}&comentarios=${encodeURIComponent(comentarios)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cotización enviada correctamente');
                modal.style.display = 'none';
                this.reset();
            } else {
                alert('Hubo un error al enviar la cotización: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error al enviar la cotización:', error);
            alert('Error al enviar la cotización');
        });
    });

    // Función para enviar mensaje
    function sendMessage() {
        const message = messageInput.value.trim();
        if (message && currentConversationId) {
            const payload = {
                id_conversacion: currentConversationId,
                contenido: message
            };

            fetch('enviarMensaje.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarMensajesNuevos();
                    messageInput.value = '';
                } else {
                    console.error('Error al enviar mensaje:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function agregarMensaje(msg) {
    if (mensajesCargados.has(msg.fecha_mensaje + msg.contenido)) return;

    const messageElement = document.createElement('div');
    messageElement.classList.add('message', msg.remitente === 'yo' ? 'sent' : 'received');

    // Manejar mensaje de cotización
    if (msg.tipo === 'cotizacion') {
        let cotizacion;
        try {
            cotizacion = JSON.parse(msg.contenido);
        } catch (error) {
            console.error('Error parsing cotizacion:', error);
            return;
        }

        // Solo mostrar botones si el mensaje es recibido (no enviado)
        const botonesHTML = msg.remitente === 'otro' ? `
            <div class="cotizacion-actions">
                <button class="btn btn-success btn-aceptar-cotizacion" 
                        data-id-cotizacion="${cotizacion.id_cotizacion}"
                        data-id-producto="${cotizacion.id_producto}">
                    Aceptar
                </button>
                <button class="btn btn-danger btn-rechazar-cotizacion" 
                        data-id-cotizacion="${cotizacion.id_cotizacion}">
                    Rechazar
                </button>
            </div>
        ` : '';

        messageElement.classList.add('cotizacion-message');
        messageElement.innerHTML = `
            <div class="cotizacion-header">
                <span>Cotización</span>
            </div>
            <div class="cotizacion-body">
                <p>Precio Unitario: $${cotizacion.precio_unitario}</p>
                <p>Cantidad: ${cotizacion.cantidad}</p>
                <p>Precio Total: $${cotizacion.precio_total}</p>
                ${cotizacion.comentarios ? `<p>Comentarios: ${cotizacion.comentarios}</p>` : ''}
                ${botonesHTML}
            </div>
        `;
    } else {
        // Mensaje de texto normal
        messageElement.innerHTML = `<p>${msg.contenido}</p>`;
    }

    chatMessages.appendChild(messageElement);
    mensajesCargados.add(msg.fecha_mensaje + msg.contenido);

    // Añadir event listeners para los botones
    if (msg.tipo === 'cotizacion' && msg.remitente === 'otro') {
        const btnAceptar = messageElement.querySelector('.btn-aceptar-cotizacion');
        const btnRechazar = messageElement.querySelector('.btn-rechazar-cotizacion');

        if (btnAceptar) {
            btnAceptar.addEventListener('click', function() {
                const idCotizacion = this.getAttribute('data-id-cotizacion');
                const idProducto = this.getAttribute('data-id-producto');
                procesarCotizacion(idCotizacion, 'aceptada', idProducto);
            });
        }

        if (btnRechazar) {
            btnRechazar.addEventListener('click', function() {
                const idCotizacion = this.getAttribute('data-id-cotizacion');
                procesarCotizacion(idCotizacion, 'rechazada');
            });
        }
    }
}

function procesarCotizacion(idCotizacion, estado, idProducto = null) {
    fetch('procesarCotizacionn.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id_cotizacion=${idCotizacion}&estado=${estado}${idProducto ? `&id_producto=${idProducto}` : ''}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (estado === 'aceptada' && idProducto) {
                // Redirigir a ResumenDeCompra.php con el ID del producto
                const cotizacionMensaje = document.querySelector(`[data-id-cotizacion="${idCotizacion}"]`).closest('.cotizacion-message');
                const cantidad = cotizacionMensaje.querySelector('.cotizacion-body p:nth-child(2)').textContent.replace(/\D/g, ''); // Extraer cantidad numérica
                const precioTotal = cotizacionMensaje.querySelector('.cotizacion-body p:nth-child(3)').textContent.replace(/[^0-9.]/g, ''); // Incluye puntos decimales

               
                const urlParams = new URLSearchParams(window.location.search);
                const id_producto_url = urlParams.get('id_producto');
                window.location.href = `ResumenDeCompra.php?id=${id_producto_url}&cantidad=${cantidad}&precio_total=${precioTotal}`;


            } else {
                alert('Cotización rechazada');
                location.reload();
            }
        } else {
            alert('Error al procesar la cotización');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la cotización');
    });
}
    // Función para cargar nuevos mensajes
    function cargarMensajesNuevos() {
        if (currentConversationId) {
            fetch(`obtenerMensajes.php?id_conversacion=${currentConversationId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(msg => agregarMensaje(msg));
                    if (data.length > 0) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                })
                .catch(error => console.error('Error al cargar nuevos mensajes:', error));
        }
    }

    // Configurar intervalo para cargar mensajes nuevos
    setInterval(cargarMensajesNuevos, 4000);

    // Event listeners para enviar mensajes
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.getElementById('cotizarForm').reset();
        }
    });
});
</script>

</body>
</html>