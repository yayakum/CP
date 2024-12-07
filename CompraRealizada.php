<?php
session_start();
require 'apiUsuarios.php';

if (isset($_GET['id_venta'])) {
    $id_venta = $_GET['id_venta'];
    
    // Instancia la clase ApiUsuarios y llama a la función
    $apiUsuarios = new ApiUsuarios();
    $productosVendidos = $apiUsuarios->getProductosVendidosByVenta($id_venta);
    
    // Verifica que haya productos en la venta
    if (!empty($productosVendidos['items'])) {
        // Toma el primer producto (asumiendo que la venta es de un solo producto)
        $producto = $productosVendidos['items'][0];
    } else {
        echo "No se encontraron productos en esta venta.";
        exit();
    }
} else {
    echo "ID de venta no encontrado.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            height: 100%;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 20%);
            z-index: -1;
            transform: translateZ(0);
        }

        .card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            max-width: 800px;
            width: 90%;
            transform: translateY(50px);
            opacity: 0;
            animation: slideUp 0.8s ease-out forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            background: #4CAF50;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .success-icon::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .success-icon svg {
            width: 40px;
            height: 40px;
            fill: none;
            stroke: white;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 80;
            stroke-dashoffset: 80;
            animation: drawCheck 1s ease-out forwards;
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(2);
                opacity: 0;
            }
        }

        .product {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .product-image {
            flex-shrink: 0;
            width: 200px;
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-image:hover img {
            transform: scale(1.1);
        }

        .product-details {
            flex-grow: 1;
        }

        h1 {
            color: #333;
            margin-bottom: 1rem;
        }

        h2 {
            color: #555;
            margin-bottom: 0.5rem;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .order-info div {
            background: rgba(255, 255, 255, 0.5);
            padding: 0.5rem;
            border-radius: 8px;
        }

        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            margin-top: 1rem;
        }

        button:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .product {
                flex-direction: column;
            }

            .product-image {
                width: 100%;
                height: 250px;
            }
        }
    </style>
</head>
<body>
<div class="background"></div>
    <div class="card">
        <div class="success-icon">
            <svg viewBox="0 0 24 24">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
        </div>
        <h1>¡Compra Realizada con Éxito!</h1>
        <div class="product">
            <div class="product-image">
                <!-- Muestra la imagen del producto -->
                <img src="data:image/jpeg;base64,<?= $producto['imagen'] ?>" alt="Producto">
            </div>
            <div class="product-details">
                <!-- Muestra el nombre del producto -->
                <h2><?= htmlspecialchars($producto['nombre_producto']) ?></h2>
                <!-- Muestra la descripción del producto -->
                <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                <div class="order-info">
                    <div>
                        <strong>Precio:</strong>
                        <p>$<?= number_format($producto['precio'], 2) ?></p>
                    </div>
                    <div>
                        <strong>Cantidad:</strong>
                        <p><?= $producto['cantidad'] ?></p>
                    </div>
                    <div>
                        <strong>Nº de Orden:</strong>
                        <p>#<?= htmlspecialchars($id_venta) ?></p>
                    </div>
                    <div>
                        <strong>Fecha:</strong>
                        <p><?= htmlspecialchars($producto['fecha_venta']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <a href="index.php">Seguir comprando en BuyHub</a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Establecer la fecha actual
            const today = new Date();
            document.getElementById('orderDate').textContent = today.toLocaleDateString();

            // Efecto de paralaje suave
            document.addEventListener('mousemove', (e) => {
                const background = document.querySelector('.background');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                background.style.transform = `translate(${x * 20}px, ${y * 20}px)`;
            });

            // Animación del botón
            const button = document.querySelector('button');
            button.addEventListener('mouseenter', () => {
                button.style.transform = 'translateY(-5px)';
            });
            button.addEventListener('mouseleave', () => {
                button.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>




