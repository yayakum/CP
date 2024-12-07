<?php

include_once 'apiUsuarios.php';
include 'navbar.php';
$api = new ApiUsuarios();

// Detectar la página de origen
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Desconocido';
$refererPage = basename(parse_url($referer, PHP_URL_PATH));

// Inicializar variables
$productos = [];
$total = 0;

// Si se proviene de Producto.php (producto individual)
if ($refererPage === 'Producto.php' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $vienecotizacion =2;

    $id_producto = $_GET['id'];
    $cantidad = $_GET['cantidad'] ?? 1; // Predeterminado a 1 si no se especifica cantidad

    // Captura el JSON de salida de la función en un buffer
    ob_start();
    $api->getProductoByIDproducto($id_producto);
    $json_data = ob_get_clean();

    $producto = json_decode($json_data, true);

    if (!empty($producto)) {
        // Procesar un solo producto
        $_SESSION['id_producto'] = $producto['id_producto'];
        $producto['cantidad'] = $cantidad;
        $productos[] = $producto;
        
        // Calcular el subtotal del producto en función de la cantidad
        $total_productos = (float)$producto['precio'] * $cantidad;
        
        // Sumar el subtotal al total y agregar el costo de envío
        $total = $total_productos + 97.00; // Suma el costo de envío
    } else {
        echo "Producto no encontrado.";
    }

// Si se proviene de Carrito.php (varios productos en el carrito)
} elseif ($refererPage === 'Cotizacion.php' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $vienecotizacion =1;
    $id_producto = $_GET['id'];
    $cantidad = $_GET['cantidad'] ?? 1; // Predeterminado a 1 si no se especifica cantidad
    $precio_total = $_GET['precio_total'];

    // Captura el JSON de salida de la función en un buffer
    ob_start();
    $api->getProductoByIDproducto($id_producto);
    $json_data = ob_get_clean();

    $producto = json_decode($json_data, true);

    if (!empty($producto)) {
        // Procesar un solo producto
        $_SESSION['id_producto'] = $producto['id_producto'];
        $producto['cantidad'] = $cantidad;
        $producto['precio_total'] = $precio_total;

        $productos[] = $producto;
        
        $total = $precio_total + 97.00;


    } else {
        echo "Producto no encontrado.";
    }

// Si se proviene de Carrito.php (varios productos en el carrito)
}
elseif ($refererPage === 'Carrito.php' && isset($_POST['productos'])) {
    $vienecotizacion =2;

    $carrito = json_decode($_POST['productos'], true);
    
    if (is_array($carrito)) {
        // Procesar cada producto en el carrito
        foreach ($carrito as $item) {
            // Obtener detalles completos del producto
            ob_start();
            $api->getProductoByIDproducto($item['id_producto']);
            $json_data = ob_get_clean();
            $producto = json_decode($json_data, true);

            if (!empty($producto)) {
                $producto['cantidad'] = $item['cantidad'];
                $productos[] = $producto;

                // Calcular el subtotal de cada producto en función de su cantidad
                $total += (float)$producto['precio'] * $item['cantidad'];
            }
        }
        // Agregar el costo de envío al total
        $total += 97.00;
    } else {
        echo "Carrito vacío o no válido.";
    }
} else {
    echo "Acceso no autorizado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Compra</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 20px;
            padding: 10px;
        }

        .product-details, .purchase-summary {
            background: white;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 24px;
            font-size: 24px;
        }

        .product-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #eee;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 4px;
        }

        .product-info h3 {
            margin-bottom: 8px;
            color: #333;
        }

        .product-quantity {
            color: #666;
            font-size: 14px;
        }

        .product-price {
            font-weight: bold;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            color: #333;
        }

        .summary-row.total {
            border-top: 1px solid #eee;
            margin-top: 12px;
            padding-top: 24px;
            font-weight: bold;
            font-size: 18px;
        }

        .coupon-link {
            color: #2d7cd0;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin: 12px 0;
        }

        .free-text {
            color: #2ea043;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="product-details">
        <h2>Detalles del producto(s)</h2>

        <?php foreach ($productos as $producto): ?>
    <div class="product-item">
        <img src="data:image/jpeg;base64,<?php echo $producto['imagenes'][0]; ?>" alt="Producto" class="product-image">
        <div class="product-info">
            <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
            <div class="product-quantity">Cantidad: <?php echo isset($producto['cantidad']) ? $producto['cantidad'] : $cantidad; ?></div>
        </div>
        <?php if ($vienecotizacion != 1) : ?>

        <div class="product-price"><?php echo htmlspecialchars($producto['precio']); ?> MXN</div>

        <?php endif; ?> 

    </div>
<?php endforeach; ?>
    </div>

    <div class="purchase-summary">
        <h2>Resumen de compra</h2>
        <div class="summary-row">
            <span>Producto(s)</span>
            <span><?php echo number_format($total - 97.00, 2); ?> MXN</span>
        </div>
        <div class="summary-row">
            <span>Envío</span>
            <span>$97.00</span>
        </div>
        <div class="summary-row total">
            <span>Pagas</span>
            <?php echo number_format($total, 2); ?> MXN
        </div>
        <div id="paypal-button-container"></div>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=AQH8relXm8sjYpBv5J_WYTWdD68faGck17hSnLccD0kGqVfTm0fcIdfhYGnnVX6bxeMmsJpBW8kS4epq&currency=MXN"></script>
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
                <?php if (count($productos) === 1): ?>
                    // Si hay un solo producto, guarda solo su id_producto
                    window.location.href = 'procesarCompra.php?paypal_order_id=' + data.orderID + '&total=<?php echo $total ?>' + '&cantidad=<?php echo $cantidad; ?>';


                <?php else: ?>
                    window.location.href = 'procesarCompra.php?total=<?php echo $total; ?>';
                <?php endif; ?>
                
               
            });
        }

    }).render('#paypal-button-container');

     
    </script>
</body>
</html>