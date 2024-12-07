<?php
include_once 'apiUsuarios.php';
include 'navbar.php';
$id = $_SESSION['id_usuario']; // Ajusta según tu lógica de obtención del ID

 $api = new ApiUsuarios;

 ob_start();
 $api->getCategorias(); 
 $CategoriasJSON = ob_get_clean();
 $Categorias = json_decode($CategoriasJSON, true);


if (isset($_POST['consultar'])) {
    // Obtener los datos del formulario
    $fecha_inicio = $_POST['start-date'];
    $fecha_fin = $_POST['end-date'];
    $categoria = $_POST['product-category'];

    // Llamar a las funciones para obtener los datos
    $ventasAgrupadas = $api->obtenerVentasAgrupadas($id,$fecha_inicio, $fecha_fin, $categoria);
    $ventasTotales = $api->obtenerVentasDetalladas($id,$fecha_inicio, $fecha_fin, $categoria);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Ventas - BuyHub</title>
    <link rel="stylesheet" href="styles.css">
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" hrdef="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="container">
    <h1>Consulta de Ventas</h1>
    
    <form id="sales-form" method="POST" action="ConsultaVentas.php">
        <label for="start-date">Fecha de inicio:</label>
        <input type="date" id="start-date" name="start-date" >
        
        <label for="end-date">Fecha de fin:</label>
        <input type="date" id="end-date" name="end-date" >
        
        <label for="product-category">Categoría:</label>
        <select id="product-category" name="product-category">
            <option value="">Todas las categorias</option>
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
        
        <button type="submit" name="consultar">Ver</button>
    </form>

    <!-- Mostrar resultados detallados -->
    <div class="results-section">
        <h2>Consulta Detallada</h2>
        <table id="detailed-results">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Categoría</th>
                    <th>Producto</th>
                    <th>Calificación</th>
                    <th>Precio</th>
                    <th>Existencia Actual</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($ventasTotales['items']) && !empty($ventasTotales['items'])): ?>
                    <?php foreach ($ventasTotales['items'] as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['fecha_venta']) ?></td>
                            <td><?= htmlspecialchars($venta['nombre_categoria']) ?></td>
                            <td><?= htmlspecialchars($venta['nombre_producto']) ?></td>
                            <td><?= htmlspecialchars($venta['valoracion']) ?></td>
                            <td><?= htmlspecialchars($venta['precio']) ?></td>
                            <td><?= htmlspecialchars($venta['cantidad_disponible']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay ventas para mostrar.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mostrar resultados agrupados -->
    <div class="results-section">
        <h2>Consulta Agrupada</h2>
        <table id="grouped-results">
            <thead>
                <tr>
                    <th>Mes-Año</th>
                    <th>Categoría</th>
                    <th>Ventas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($ventasAgrupadas['items']) && !empty($ventasAgrupadas['items'])): ?>
                    <?php foreach ($ventasAgrupadas['items'] as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['mes_ano']) ?></td>
                            <td><?= htmlspecialchars($venta['categoria']) ?></td>
                            <td><?= htmlspecialchars($venta['ventas_totales']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No hay ventas agrupadas para mostrar.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>