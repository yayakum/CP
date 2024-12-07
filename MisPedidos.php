<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

$id = $_SESSION['id_usuario']; // Ajusta según tu lógica de obtención del ID

$apiUsuarios = new ApiUsuarios();


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
    <title>Mis Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pedidos-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .pedidos-container h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .filtros {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .filtro-categoria, .filtro-fecha {
            width: 48%;
        }
        .filtros label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .filtros select, .filtros input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background-color: #007bff;
            color: #fff;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
        }
        .table tbody tr:hover {
            background-color: #f1f3f5;
        }
        .bi-star-fill {
            color: #ffc107;
        }
        .bi-star-half {
            color: #ffc107;
        }
        .bi-star {
            color: #e4e5e9;
        }
        .btn-ver {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="pedidos-container">
    <h2>Mis Pedidos</h2>

    <form method="GET" action="MisPedidos.php"> <!-- Aquí usamos GET para enviar los parámetros -->
        <div class="filtros">
            <div class="filtro-categoria">
                <label for="filtroCategoria">Filtrar por Categoría:</label>
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
            </div>
            <div class="filtro-fecha">
                <label for="filtroFechaInicio">Filtrar por Rango de Fechas:</label>
                <div class="d-flex gap-2">
                    <input type="date" id="filtroFechaInicio" name="fecha_inicio" class="form-control" placeholder="Fecha inicio">
                    <input type="date" id="filtroFechaFin" name="fecha_fin" class="form-control" placeholder="Fecha fin">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-ver">Ver</button> <!-- Cambié el tipo de botón a submit -->
    </form>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Categoría</th>
                <th>Producto</th>
                <th>Calificación</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Aquí recogemos los datos del formulario
                $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
                $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
                $id_categoria = isset($_GET['product-category']) ? $_GET['product-category'] : null;
                
                // Llamamos a la función para obtener los pedidos filtrados
                $misPedidos = $apiUsuarios->getMisPedidos($id, $fecha_inicio, $fecha_fin, $id_categoria); // Asumí que el ID del comprador es 1

                if (!empty($misPedidos['items'])) {
                    foreach ($misPedidos['items'] as $pedido) {
                        echo "<tr>
                            <td>{$pedido['fecha_compra']}</td>
                            <td>{$pedido['categoria']}</td>
                            <td>{$pedido['producto']}</td>
                            <td>";
                        for ($i = 0; $i < $pedido['valoracion']; $i++) {
                            echo "<i class='bi bi-star-fill'></i>";
                        }
                        for ($i = 0; $i < 5 - $pedido['valoracion']; $i++) {
                            echo "<i class='bi bi-star'></i>";
                        }
                        echo "</td>
                            <td>\${$pedido['precio']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay pedidos para mostrar.</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>