<?php
session_start(); // Asegúrate de iniciar la sesión para almacenar los datos

include_once 'apiUsuarios.php';
include 'navbar.php';
$api = new ApiUsuarios();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto = $_GET['id']; // Obtiene el ID del producto

    // Captura el JSON de salida de la función en un buffer
    ob_start();
    $api->getProductoByIDproducto($id_producto);
    $json_data = ob_get_clean(); 

    $producto = json_decode($json_data, true);

    if (!empty($producto)) {
        // Almacenar el id_producto en la sesión
        $_SESSION['id_producto'] = $producto['id_producto'];
        
        // Obtener el resto de la información del producto
        $nombre_producto = $producto['nombre_producto'];
        $descripcion = $producto['descripcion'];
        $cantidad = $producto['cantidad_disponible'];
        $precio = $producto['precio'];
        $imagenes = $producto['imagenes']; 
        $estado = $producto['estado'];  
        
        // Resto del código de visualización
    } else {
        echo "Producto no encontrado.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra de Producto - BuyHub</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

   <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .product-details {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .main-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .product-info h2 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #4a4a4a;
        }
        .payment-methods {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .payment-methods h3 {
            margin-top: 0;
            color: #333;
        }
        .payment-option {
            margin-bottom: 10px;
        }
        .payment-option label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .payment-option input[type="radio"] {
            margin-right: 10px;
        }
        #paypal-email {
            display: none;
            margin-top: 10px;
        }
        #paypal-email input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-purchase {
            background-color: #143EE0;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .btn-purchase:hover {
            background-color: #45a049;
        }
        .summary {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .summary h3 {
            margin-top: 0;
            color: #333;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-total {
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
 
    <div class="container">
    <div class="product-details">
        <img src="data:image/jpeg;base64,<?php echo $imagenes[0]; ?>" alt="Producto Principal" class="main-image" id="mainImage">
        <div class="product-info">
            <h2><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
            <p class="product-price"><?php echo htmlspecialchars($producto['precio']); ?> MXN</p>
        </div>
    </div>

    <div class="summary">
        <h3>Resumen de compra</h3>
        <div class="summary-item">
            <span>Producto(s)</span>
            <span><?php echo htmlspecialchars($producto['precio']); ?> MXN</span>
        </div>
        <div class="summary-item">
            <span>Envío</span>
            <span>$97.00</span>
        </div>
        <div class="summary-item summary-total">
            <span>Total</span>
            <span>
                <?php
                    $precioProducto = (float) $producto['precio']; // Convierte el precio del producto a un valor numérico
                    $envio = 97.00; // Costo de envío fijo
                    $total = $precioProducto + $envio; // Calcula el total
                    echo number_format($total, 2); // Muestra el total con 2 decimales
                ?>
                MXN
            </span>
        </div>
    </div>
   
    <div class="payment-methods">
    <h3>Selecciona un método de pago</h3>
    
    <!-- Botón de PayPal siempre visible -->
    <div id="paypal-button-container"></div>
    
    <!-- Opción para pagar en efectivo en Oxxo -->
    <div class="payment-option">
        <label>
            <input type="radio" name="payment" value="oxxo" onclick="hidePayPalButton()">
            Efectivo en Oxxo
        </label>
    </div>
</div>
<script src="https://www.paypal.com/sdk/js?client-id=AQH8relXm8sjYpBv5J_WYTWdD68faGck17hSnLccD0kGqVfTm0fcIdfhYGnnVX6bxeMmsJpBW8kS4epq&currency=MXN"></script>


<form action="procesarCompra.php" method="POST">
    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
    <input type="hidden" name="total" value="<?php echo $total; ?>">
    <button type="submit" class="btn-purchase">Realizar Compra</button>
</form>

</div>


    <script>
      paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $total; ?>' // Total de la compra en MXN
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                sessionStorage.setItem('id_producto', '<?php echo $producto['id_producto']; ?>');
                
                window.location.href = 'procesarCompra.php?paypal_order_id=' + data.orderID + '&total=<?php echo $total; ?>';
            });
        }
    }).render('#paypal-button-container');

        function showPayPalEmail() {
            document.getElementById('paypal-email').style.display = 'block';
        }

        function hidePayPalEmail() {
            document.getElementById('paypal-email').style.display = 'none';
        }
    </script>
</body>
</html>