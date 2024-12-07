<?php
include_once 'apiUsuarios.php';
include 'navbar.php';
$id = $_SESSION['id_usuario']; // Ajusta según tu lógica de obtención del ID
$api = new ApiUsuarios();



$carrito = $api->getCarrito($id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
   
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --primary-color: #143ee0;
            --secondary-color: #f9f9f9;
            --accent-color: #3498db;
            --text-color: #333;
            --border-color: #e0e0e0;
        }

        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--secondary-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

 
       
        main {
            padding: 40px 0;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .cart-container {
            display: flex;
            gap: 40px;
        }

        .cart-items {
            flex: 2;
        }

        .cart-item {
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
        }

        .item-details {
            flex: 1;
        }

        .item-details h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .item-price {
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            background-color: var(--secondary-color);
            border: 1px solid var(--border-color);
            color: var(--primary-color);
            font-size: 16px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .quantity-btn:hover {
            background-color: var(--border-color);
        }

        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid var(--border-color);
            margin: 0 5px;
        }

        .remove-item {
            background: none;
            border: none;
            color: #999;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .remove-item:hover {
            color: #ff6b6b;
        }

        .cart-summary {
            flex: 1;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            align-self: flex-start;
        }

        .cart-summary h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-item.total {
            font-weight: 700;
            font-size: 18px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: var(--accent-color);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: var(--primary-color);
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .cart-container {
                flex-direction: column;
            }

            .cart-summary {
                order: -1;
                margin-bottom: 20px;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .item-image {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .remove-item {
                align-self: flex-end;
                margin-top: -30px;
            }
        }
    </style>


</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />


  <br>   <br>   <br>   <br>

<main class="container">
    <h1>Tu Carrito</h1>
    <div class="cart-container">
        <div class="cart-items">
            <?php foreach ($carrito as $item): ?>
                <div class="cart-item">
                    <!-- Mostrar imagen del producto -->
                    <?php if (!empty($item['imagen'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo $item['imagen']; ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>" class="item-image">
                    <?php else: ?>
                        <p>No hay imagen disponible</p>
                    <?php endif; ?>

                    <!-- Mostrar detalles del producto -->
                    <div class="item-details">
                        
                        <h2><?php echo htmlspecialchars($item['nombre_producto']); ?></h2>
                        <p class="item-price">$<?php echo number_format($item['precio'], 2); ?></p>
                        <div class="quantity-control">
                            <button class="quantity-btn minus">-</button>
                            <input type="number" value="<?php echo $item['cantidad']; ?>" min="1" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>
                    </div>
                    <button class="remove-item" data-id="<?php echo $item['id_producto']; ?>">×</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
                
            </div>
            <div class="cart-summary">
                <h2>Resumen de Compra</h2>
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span id="subtotal">$44.98</span>
                </div>
                <div class="summary-item">
                    <span>Envío:</span>
                    <span>$97.00</span>
                </div>
                <div class="summary-item total">
                    <span>Total:</span>
                    <span id="total">$49.98</span>
                </div>
                            <form action="ResumenDeCompra.php" method="POST">
                <!-- Convertimos el carrito con las cantidades actualizadas -->
                <input type="hidden" id="carritoData" name="productos" value='<?php echo json_encode($carrito); ?>'>
                <button type="submit" class="checkout-btn">Proceder al Pago</button>
            </form>

            </div>
        </div>
    </main>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const removeButtons = document.querySelectorAll('.remove-item');
    const checkoutBtn = document.querySelector('.checkout-btn');
    const minusButtons = document.querySelectorAll('.quantity-btn.minus');
    const plusButtons = document.querySelectorAll('.quantity-btn.plus');

    function updateCartTotal() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseFloat(item.querySelector('.item-price').textContent.replace('$', '').replace(',', ''));
            const quantity = parseInt(item.querySelector('.quantity-input').value);

            if (!isNaN(price) && !isNaN(quantity)) {
                subtotal += price * quantity;
            }
        });

        const shipping = 97.00; // Precio fijo de envío
        const total = subtotal + shipping;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
    }

    // Actualizar total cuando cambia la cantidad
    quantityInputs.forEach(input => {
        input.addEventListener('input', () => {
            if (parseInt(input.value) < 1 || isNaN(parseInt(input.value))) {
                input.value = 1; // Evitar cantidades negativas
            }
            updateCartTotal();
        });
    });

    // Eliminar producto del carrito
    removeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const cartItem = e.target.closest('.cart-item');
            const productId = e.target.getAttribute('data-id');

            fetch('RemoveFromCarrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_producto=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartItem.remove();
                    updateCartTotal();
                } else {
                    alert('Error al eliminar el producto del carrito.');
                }
            })
            .catch(error => console.error('Error en fetch:', error));
        });
    });

    // Ajustar cantidad con botones + y -
    minusButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const input = e.target.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('input')); // Actualiza el subtotal
            }
        });
    });

    plusButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const input = e.target.previousElementSibling;
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('input')); // Actualiza el subtotal
        });
    });

    // Inicializar totales al cargar la página
    updateCartTotal();

    // Al hacer clic en "Proceder al Pago", llenar el campo oculto con los datos del carrito
   
});
</script>




</body>
</html>