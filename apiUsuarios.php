<?php
include_once 'usuario.php'; 

class ApiUsuarios {

    function getAll() {
        $usuario = new Usuario();
        $usuarios = array();
        $usuarios["items"] = array();

        $res = $usuario->obtenerUsuarios();

        if($res->num_rows > 0) {

            while($row = $res->fetch_assoc()) {
                $item = array(
                    'nombre_usuario' => $row['nombre_usuario'],
                    'contraseña' => $row['contraseña'],
                );
                array_push($usuarios['items'], $item);
            }

            $this->printJSON($usuarios);
        } else {
            $this->error('No hay elementos registrados');
        }
    }

   
    function getbyID($id) {
        $usuario = new Usuario();
        $usuarios = array();
        $usuarios["items"] = array();

        $res = $usuario->obtenerUsuario($id);

        if ($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                $item = array(
                    'nombre_usuario' => $row['nombre_usuario'],
                    'correo' => $row['correo'],
                    'sexo' => $row['sexo'],
                    'avatar' =>$row['avatar'],
                    'fecha_nacimiento' =>$row['fecha_nacimiento'],
                    'rol' =>$row['id_rol'],
                    'publico_privado' => $row['publico_privado'],
                    'contraseña' => $row['contraseña'],
                    'nombre_completo' => $row['nombre_completo'],



                );
                array_push($usuarios['items'], $item);
            }

            return $usuarios;
        } else {
            return array('mensaje' => 'No hay elementos registrados');
        }
    }

    

    function validarCredenciales($correo, $contrasena) {
        $usuario = new Usuario();
        $res = $usuario->validarCredenciales($correo, $contrasena);

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $item = array(
                'id_usuario' => $row['id_usuario'],  // Retornamos el id_usuario para la sesión
                'nombre_usuario' => $row['nombre_usuario'],
                'correo' => $row['correo'],
            );
            return $item;  
        } else {
            return array('mensaje' => 'Credenciales incorrectas');
        }
    }
    
    function getCategorias() {
        $usuario = new Usuario();
        $categorias = array();
        $categorias["items"] = array();
    
        $res = $usuario->obtenerCategorias();
    
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $item = array(
                    'id_categoria' => $row['id_categoria'],
                    'nombre_categoria' => $row['nombre_categoria'],
                    'imagen' => base64_encode($row['imagen']) // Codifica en base64 si es un BLOB
                );
                array_push($categorias['items'], $item);
            }
    
            echo json_encode($categorias); 
        } else {
            echo json_encode(array('items' => array())); 
        }
    }
    public function getProductosByVendedorYCategoria($id_vendedor, $id_categoria = null) {
        $usuario = new Usuario();
        $productos = array();
        $productos["items"] = array();
    
        $query = "
            SELECT p.*, 
                   c.contenido AS imagen
            FROM Productos p
            LEFT JOIN Contenido c 
                ON p.id_producto = c.id_producto 
                AND c.tipo_contenido LIKE 'image/%'
                AND c.id_contenido = (
                    SELECT MIN(id_contenido) 
                    FROM Contenido 
                    WHERE id_producto = p.id_producto 
                    AND tipo_contenido LIKE 'image/%'
                )
            WHERE p.id_vendedor = ? 
        ";
    
        // Si se especifica una categoría, agregar el filtro de categoría
        if ($id_categoria) {
            $query .= " AND p.id_categoria = ?";
        }
    
        // Preparar y ejecutar la consulta
        $stmt = $usuario->conexion->prepare($query);
        if ($id_categoria) {
            $stmt->bind_param("ii", $id_vendedor, $id_categoria);  // "ii" indica que ambos parámetros son enteros
        } else {
            $stmt->bind_param("i", $id_vendedor);  // Solo el filtro de vendedor
        }
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Verificar los resultados y almacenar los datos en el arreglo
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'id_producto' => $row['id_producto'],
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio'],
                    'cantidad_disponible' => $row['cantidad_disponible'],
                    'imagen' => $row['imagen'] ? base64_encode($row['imagen']) : null,
                    'autorizado' => $row['autorizado'],


                );
                array_push($productos['items'], $item);
            }
        } else {
            $productos['items'] = []; // Asegura que siempre se devuelve un arreglo vacío si no hay resultados
        }
    
        return $productos; // Devuelve el arreglo con todos los productos encontrados
    }

    public function getAllAdmin(){
        $usuario = new Usuario();
        $productos = array();
        $productos["items"] = array();
        
        $query = "SELECT * FROM Productos WHERE autorizado = 0";

        $stmt = $usuario->conexion->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'id_producto' => $row['id_producto'],
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio'],
                    'cantidad_disponible' => $row['cantidad_disponible'],
                    'valoracion' => $row['valoracion'],
                    'comentarios' => $row['comentarios'],
                    'id_categoria' => $row['id_categoria'],
                    'id_vendedor' => $row['id_vendedor'],
                    'estado' => $row['estado'],
                    'autorizado' => $row['autorizado'],
                );
                array_push($productos['items'], $item);
            }
    
            echo json_encode($productos); // Devuelve el JSON con todos los productos
        } else {
            echo json_encode(array('items' => array())); // Devuelve un array vacío
        }

    }


    function getProductosbyID($id) {
        $usuario = new Usuario();
        $productos = array();
        $productos["items"] = array();
    
                    // Consulta para obtener todos los productos del vendedor y su imagen
                    $query = "SELECT p.*, c.contenido AS imagen
                    FROM Productos p
                    LEFT JOIN Contenido c 
                        ON p.id_producto = c.id_producto 
                        AND c.tipo_contenido LIKE 'image/%'
                        AND c.id_contenido = (
                            SELECT MIN(id_contenido) 
                            FROM Contenido 
                            WHERE id_producto = p.id_producto 
                            AND tipo_contenido LIKE 'image/%'
                        )
                    WHERE p.id_vendedor = ?;";
                // Obtiene todos los productos del vendedor
    
        $stmt = $usuario->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'id_producto' => $row['id_producto'],
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio'],
                    'cantidad_disponible' => $row['cantidad_disponible'],
                    'valoracion' => $row['valoracion'],
                    'comentarios' => $row['comentarios'],
                    'id_categoria' => $row['id_categoria'],
                    'id_vendedor' => $row['id_vendedor'],
                    'estado' => $row['estado'],
                    'autorizado' => $row['autorizado'],
                    'imagen' => base64_encode($row['imagen']) // Convierte la imagen a base64
                );
                array_push($productos['items'], $item);
            }
    
            echo json_encode($productos); // Devuelve el JSON con todos los productos
        } else {
            echo json_encode(array('items' => array())); // Devuelve un array vacío
        }
    }

    function getAllProductos() {
        $usuario = new Usuario();
        $productos = array();
        $productos["items"] = array();
    
        // Consulta para obtener todos los productos y su imagen
        $query = "SELECT p.*, c.contenido AS imagen
                  FROM Productos p
                  LEFT JOIN Contenido c 
                      ON p.id_producto = c.id_producto 
                      AND c.tipo_contenido LIKE 'image/%'
                      AND c.id_contenido = (
                          SELECT MIN(id_contenido) 
                          FROM Contenido 
                          WHERE id_producto = p.id_producto 
                          AND tipo_contenido LIKE 'image/%'
                      );";
    
        $stmt = $usuario->conexion->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'id_producto' => $row['id_producto'],
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio'],
                    'cantidad_disponible' => $row['cantidad_disponible'],
                    'valoracion' => $row['valoracion'],
                    'comentarios' => $row['comentarios'],
                    'id_categoria' => $row['id_categoria'],
                    'id_vendedor' => $row['id_vendedor'],
                    'estado' => $row['estado'],
                    'autorizado' => $row['autorizado'],
                    'imagen' => base64_encode($row['imagen']) // Convierte la imagen a base64
                );
                array_push($productos['items'], $item);
            }
    
            echo json_encode($productos); // Devuelve el JSON con todos los productos
        } else {
            echo json_encode(array('items' => array())); // Devuelve un array vacío
        }
    }
    public function getProductoByIDproducto($id_producto) {
        $usuario = new Usuario();
        $producto = array();
        
        // Consulta para obtener la información del producto
        $queryProducto = "
        SELECT p.*, c.nombre_categoria 
        FROM Productos p 
        JOIN Categorias c ON p.id_categoria = c.id_categoria 
        WHERE p.id_producto = ?;";
        $stmtProducto = $usuario->conexion->prepare($queryProducto);
        $stmtProducto->bind_param("i", $id_producto);
        $stmtProducto->execute();
        $resultProducto = $stmtProducto->get_result();
        $rowProducto = $resultProducto->fetch_assoc();

        if ($rowProducto) {
            // Construye el arreglo principal con la información del producto
            $producto = array(
                'id_producto' => $rowProducto['id_producto'],
                'nombre_producto' => $rowProducto['nombre_producto'],
                'descripcion' => $rowProducto['descripcion'],
                'precio' => $rowProducto['precio'],
                'cantidad_disponible' => $rowProducto['cantidad_disponible'],
                'valoracion' => $rowProducto['valoracion'],
                'comentarios' => $rowProducto['comentarios'],
                'id_categoria' => $rowProducto['id_categoria'],
                'nombre_categoria' => $rowProducto['nombre_categoria'],
                'id_vendedor' => $rowProducto['id_vendedor'],
                'estado' => $rowProducto['estado'],
                'autorizado' => $rowProducto['autorizado'],
                'imagenes' => array() // Array para almacenar las imágenes
            );

            // Consulta para obtener todas las imágenes del producto
            $queryImagenes = "SELECT contenido FROM Contenido WHERE id_producto = ? AND tipo_contenido LIKE 'image/%';";
            $stmtImagenes = $usuario->conexion->prepare($queryImagenes);
            $stmtImagenes->bind_param("i", $id_producto);
            $stmtImagenes->execute();
            $resultImagenes = $stmtImagenes->get_result();

            // Recorre todas las imágenes y las agrega al array de imágenes
            while ($rowImagen = $resultImagenes->fetch_assoc()) {
                $producto['imagenes'][] = base64_encode($rowImagen['contenido']); // Codifica cada imagen en base64
            }

                $queryVideo = "SELECT contenido FROM Contenido WHERE id_producto = ? AND tipo_contenido LIKE 'video/%';";
                $stmtVideo = $usuario->conexion->prepare($queryVideo);
                $stmtVideo->bind_param("i", $id_producto);
                $stmtVideo->execute();
                $resultVideo = $stmtVideo->get_result();

                // Verifica si hay un video y lo agrega al arreglo
                if ($rowVideo = $resultVideo->fetch_assoc()) {
                    $producto['video'] = base64_encode($rowVideo['contenido']); // Codifica el video en base64
                }

            echo json_encode($producto); // Devuelve el JSON con la información del producto y sus imágenes
        } else {
            echo json_encode(array()); // Devuelve un JSON vacío si no se encuentra el producto
        }
    }
    

    function getCarrito($id_usuario) {
        $usuario = new Usuario();
    
        // Array para almacenar los productos en el carrito
        $carrito = array();
    
        // Consulta para obtener el ID del carrito del usuario
        $queryCarrito = "SELECT id_carrito FROM Carrito WHERE id_usuario = ?";
        $stmtCarrito = $usuario->conexion->prepare($queryCarrito);
        $stmtCarrito->bind_param("i", $id_usuario);
        $stmtCarrito->execute();
        $resultCarrito = $stmtCarrito->get_result();
    
        if ($resultCarrito->num_rows > 0) {
            $rowCarrito = $resultCarrito->fetch_assoc();
            $id_carrito = $rowCarrito['id_carrito'];
    
            // Consulta para obtener los productos en el carrito, con el nombre, precio y la imagen con el id_contenido más bajo
            $queryProductos = "
                SELECT 
                    p.id_producto,
                    p.nombre_producto,
                    p.precio,
                    pc.cantidad,
                    c.contenido AS imagen
                FROM Productos_en_carrito pc
                JOIN Productos p ON pc.id_producto = p.id_producto
                LEFT JOIN Contenido c ON c.id_producto = p.id_producto
                    AND c.tipo_contenido LIKE 'image/%'
                    AND c.id_contenido = (
                        SELECT MIN(id_contenido)
                        FROM Contenido
                        WHERE id_producto = p.id_producto 
                        AND tipo_contenido LIKE 'image/%'
                    )
                WHERE pc.id_carrito = ?;";
    
            $stmtProductos = $usuario->conexion->prepare($queryProductos);
            $stmtProductos->bind_param("i", $id_carrito);
            $stmtProductos->execute();
            $resultProductos = $stmtProductos->get_result();
    
            // Agregar cada producto al array del carrito
            while ($producto = $resultProductos->fetch_assoc()) {
                $carrito[] = array(
                    'id_producto' => $producto['id_producto'],
                    'nombre_producto' => $producto['nombre_producto'],
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad'],
                    'imagen' => base64_encode($producto['imagen']) // Codifica la imagen en base64
                );
            }
        }
    
        return $carrito;
    }
    
    public function id_vendedor($id_vendedor) {
        $usuario = new Usuario();
        $vendedor = array();

        // Consulta para obtener la información del vendedor
        $queryVendedor = "SELECT nombre_usuario, correo, avatar FROM Usuarios WHERE id_usuario = ?";
        $stmtVendedor = $usuario->conexion->prepare($queryVendedor);
        $stmtVendedor->bind_param("i", $id_vendedor);
        $stmtVendedor->execute();
        $resultVendedor = $stmtVendedor->get_result();

        // Verifica si el vendedor existe y agrega su información al array
        if ($rowVendedor = $resultVendedor->fetch_assoc()) {
            $vendedor = array(
                'nombre_usuario' => $rowVendedor['nombre_usuario'],
                'correo' => $rowVendedor['correo'],
                'avatar' => $rowVendedor['avatar']
            );
            return json_encode($vendedor);
        } else {
            return json_encode(array('mensaje' => 'Vendedor no encontrado'));
        }
    }

    function getListasByUsuario($id_usuario) {
        $usuario = new Usuario();
        $listas = array();
        $listas["items"] = array();
    
        // Consulta para obtener todas las listas del usuario, la imagen del primer producto y el conteo de productos en cada lista
        $query = "
            SELECT 
                l.id_lista,
                l.nombre_lista,
                l.descripcion,
                l.publica_privada,
                (SELECT c.contenido 
                 FROM Contenido c 
                 JOIN Productos_en_lista pl ON pl.id_producto = c.id_producto
                 WHERE pl.id_lista = l.id_lista 
                 AND c.tipo_contenido = 'image/jpeg'
                 ORDER BY c.id_contenido ASC 
                 LIMIT 1) AS imagen_producto,
                (SELECT COUNT(pl.id_producto) 
                 FROM Productos_en_lista pl 
                 WHERE pl.id_lista = l.id_lista) AS total_productos
            FROM 
                Listas l
            WHERE 
                l.id_usuario = ?;
        ";
    
        $stmt = $usuario->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'id_lista' => $row['id_lista'],
                    'nombre_lista' => $row['nombre_lista'],
                    'descripcion' => $row['descripcion'],
                    'publica_privada' => $row['publica_privada'],
                    'imagen_producto' => $row['imagen_producto'] ? base64_encode($row['imagen_producto']) : null,
                    'total_productos' => $row['total_productos']
                );
                array_push($listas['items'], $item);
            }
        } else {
            $listas['items'] = []; // Esto asegura que siempre se devuelve un arreglo
        }
    
        return $listas; // Devuelve el arreglo con todas las listas, la imagen del primer producto y el conteo de productos
    }
    
    
    public function getProductosEnLista($id_lista) {
        $usuario = new Usuario();
    
        // Array para almacenar los productos de la lista
        $productosLista = array();
    
        // Consulta para obtener los productos en la lista, con el nombre, precio, imagen del producto y nombre de la lista
        $queryProductos = "
            SELECT 
                p.id_producto,
                p.nombre_producto,
                p.precio,
                c.contenido AS imagen_producto,  -- Imagen del producto (solo una)
                l.nombre_lista
            FROM 
                Productos p
            JOIN 
                Productos_en_lista pl ON p.id_producto = pl.id_producto
            JOIN 
                Listas l ON l.id_lista = pl.id_lista
            LEFT JOIN 
                Contenido c ON c.id_producto = p.id_producto 
                AND c.tipo_contenido = 'image/jpeg'
                AND c.id_contenido = (
                    SELECT MIN(c2.id_contenido) 
                    FROM Contenido c2 
                    WHERE c2.id_producto = p.id_producto 
                    AND c2.tipo_contenido = 'image/jpeg'
                )
            WHERE 
                l.id_lista = ?  -- Filtra por el id_lista que se pasa
            ORDER BY 
                p.nombre_producto;
        ";
    
        $stmtProductos = $usuario->conexion->prepare($queryProductos);
        $stmtProductos->bind_param("i", $id_lista);  // El parámetro es un entero, el id de la lista
        $stmtProductos->execute();
        $resultProductos = $stmtProductos->get_result();
    
        // Agregar cada producto al array de productos de la lista
        while ($producto = $resultProductos->fetch_assoc()) {
            $productosLista[] = array(
                'id_producto' => $producto['id_producto'],
                'nombre_producto' => $producto['nombre_producto'],
                'precio' => $producto['precio'],
                'imagen_producto' => base64_encode($producto['imagen_producto']), // Codifica la imagen en base64
                'nombre_lista' => $producto['nombre_lista']
            );
        }
    
        return $productosLista;
    }
    public function getProductosVendidosByVenta($id_venta) {
        $usuario = new Usuario();
        $productosVendidos = array();
        $productosVendidos["items"] = array();

        // Consulta para obtener los detalles de los productos vendidos en una venta específica
        $query = "
            SELECT 
                p.nombre_producto, 
                p.descripcion, 
                pv.precio, 
                pv.cantidad, 
                pv.fecha_venta,
                c.contenido AS imagen
            FROM 
                Productos p
            JOIN 
                Productos_vendidos pv ON p.id_producto = pv.id_producto
            JOIN 
                Contenido c ON p.id_producto = c.id_producto
            WHERE 
                pv.id_venta = ?
            ORDER BY 
                c.id_contenido ASC
            LIMIT 1
        ";

        // Preparar y ejecutar la consulta
        $stmt = $usuario->conexion->prepare($query);
        $stmt->bind_param("i", $id_venta);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar los resultados y almacenar los datos en el arreglo
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio'],
                    'cantidad' => $row['cantidad'],
                    'fecha_venta' => $row['fecha_venta'],
                    'imagen' => $row['imagen'] ? base64_encode($row['imagen']) : null
                );
                array_push($productosVendidos['items'], $item);
            }
        } else {
            $productosVendidos['items'] = []; // Asegura que siempre se devuelve un arreglo
        }

        return $productosVendidos; // Devuelve el arreglo con todos los productos vendidos y sus detalles
    }
    
    public function haCompradoProducto($id_producto, $id_usuario) {
        // Conexión a la base de datos
        $usuario = new Usuario();

        // Consulta SQL
        $query = "SELECT COUNT(*) AS ha_comprado 
                  FROM Productos_vendidos 
                  WHERE id_producto = ? AND id_venta IN (
                      SELECT id_venta FROM Ventas WHERE id_usuario_comprador = ?
                  )";

        // Preparar y ejecutar la consulta
        $stmt = $usuario->conexion->prepare($query);
        $stmt->bind_param("ii", $id_producto, $id_usuario);
        $stmt->execute();
        
        // Obtener el resultado
        $stmt->bind_result($ha_comprado);
        $stmt->fetch();
        
        // Cerrar la conexión
        $stmt->close();

        // Retornar el resultado
        return $ha_comprado;
    }

    public function getComentarios($id_producto) {
        $usuario = new Usuario();
        $comentarios = array();
    
        // Consulta para obtener los comentarios, nombre del usuario y avatar
        $queryComentarios = "
        SELECT 
            c.comentario,
            c.calificacion,
            c.fecha_comentario,
            u.nombre_usuario,
            u.avatar
        FROM 
            Comentarios c
        JOIN 
            Usuarios u ON c.id_usuario = u.id_usuario
        WHERE 
            c.id_producto = ?
        ORDER BY 
            c.fecha_comentario DESC;
        ";
        
        // Prepara y ejecuta la consulta
        $stmtComentarios = $usuario->conexion->prepare($queryComentarios);
        $stmtComentarios->bind_param("i", $id_producto);
        $stmtComentarios->execute();
        $resultComentarios = $stmtComentarios->get_result();
    
        // Recorre los resultados y agrega cada comentario al array de comentarios
        while ($rowComentario = $resultComentarios->fetch_assoc()) {
            $comentarios[] = array(
                'comentario' => $rowComentario['comentario'],
                'calificacion' => $rowComentario['calificacion'],
                'fecha_comentario' => $rowComentario['fecha_comentario'],
                'nombre_usuario' => $rowComentario['nombre_usuario'],
                'avatar' => $rowComentario['avatar']
            );
        }
        
        
        echo json_encode($comentarios);
    }
    
    public function getValoracionProducto($id_producto) {
        $usuario = new Usuario();
        $productoInfo = array();
        
        // Consulta para obtener la valoración y la cantidad de comentarios del producto
        $query = "
            SELECT valoracion, comentarios 
            FROM Productos 
            WHERE id_producto = ?;
        ";
        $stmt = $usuario->conexion->prepare($query);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row) {
            $productoInfo = array(
                'valoracion' => $row['valoracion'],
                'comentarios' => $row['comentarios']
            );
        }
        
        echo json_encode($productoInfo); // Devuelve los datos en formato JSON
    }



    public function buscarPorNombre($termino_busqueda) {
        $usuario = new Usuario();
    
        // Dividir el término de búsqueda en palabras separadas por espacio
        $palabras = explode(' ', $termino_busqueda);
    
        // Crear un array de condiciones SQL para las palabras en nombre_producto y nombre_usuario
        $condiciones_productos = [];
        $condiciones_usuarios = [];
        
        foreach ($palabras as $palabra) {
            // Condición para productos
            $condiciones_productos[] = "p.nombre_producto LIKE ?";
            // Condición para usuarios
            $condiciones_usuarios[] = "u.nombre_usuario LIKE ?";
        }
    
        // Consultas para productos
        $sql_productos = "SELECT p.*, 
                                 c.contenido AS imagen, 
                                 u.nombre_usuario AS vendedor
                          FROM Productos p
                          LEFT JOIN Contenido c ON p.id_producto = c.id_producto 
                                                 AND c.tipo_contenido = 'image/jpeg'
                          LEFT JOIN Usuarios u ON p.id_vendedor = u.id_usuario
                          WHERE " . implode(' AND ', $condiciones_productos) . " 
                          AND c.id_contenido = (
                              SELECT MIN(c2.id_contenido)
                              FROM Contenido c2
                              WHERE c2.id_producto = p.id_producto
                              AND c2.tipo_contenido = 'image/jpeg')
                          ORDER BY p.id_producto";
    
        // Consultas para usuarios
        $sql_usuarios = "SELECT u.id_usuario, u.nombre_usuario, u.correo, u.avatar
                         FROM Usuarios u
                         WHERE " . implode(' AND ', $condiciones_usuarios);
    
        // Preparar y ejecutar la consulta de productos
        $stmt_productos = $usuario->conexion->prepare($sql_productos);
        $parametros_productos = [];
        foreach ($palabras as $palabra) {
            $parametros_productos[] = '%' . $palabra . '%';
        }
        $types_productos = str_repeat('s', count($palabras));  
        $stmt_productos->bind_param($types_productos, ...$parametros_productos);
        $stmt_productos->execute();
        $resultados_productos = $stmt_productos->get_result();
    
        // Preparar y ejecutar la consulta de usuarios
        $stmt_usuarios = $usuario->conexion->prepare($sql_usuarios);
        $parametros_usuarios = [];
        foreach ($palabras as $palabra) {
            $parametros_usuarios[] = '%' . $palabra . '%';
        }
        $types_usuarios = str_repeat('s', count($palabras));  
        $stmt_usuarios->bind_param($types_usuarios, ...$parametros_usuarios);
        $stmt_usuarios->execute();
        $resultados_usuarios = $stmt_usuarios->get_result();
    
        // Arreglo para almacenar los resultados
        $resultados = [
            'productos' => [],
            'usuarios' => []
        ];
    
        // Procesar los resultados de productos
        if ($resultados_productos->num_rows > 0) {
            while ($row = $resultados_productos->fetch_assoc()) {
                $producto = array(
                    'nombre_producto' => $row['nombre_producto'],
                    'descripcion' => $row['descripcion'],
                    'precio' => $row['precio'],
                    'cantidad_disponible' => $row['cantidad_disponible'],
                    'valoracion' => $row['valoracion'],
                    'comentarios' => $row['comentarios'],
                    'vendedor' => $row['vendedor'],
                    'id_producto' => $row['id_producto'],
                    'autorizado' => $row['autorizado'],
                    'imagen' => $row['imagen'] ? base64_encode($row['imagen']) : null
                );
                array_push($resultados['productos'], $producto);
            }
        }
    
        // Procesar los resultados de usuarios
        if ($resultados_usuarios->num_rows > 0) {
            while ($row = $resultados_usuarios->fetch_assoc()) {
                $usuario = array(
                    'id_usuario' => $row['id_usuario'],
                    'nombre_usuario' => $row['nombre_usuario'],
                    'email' => $row['correo'],
                    'avatar'=>$row['avatar']
                );
                array_push($resultados['usuarios'], $usuario);
            }
        }
    
        // Cerrar los statement
        $stmt_productos->close();
        $stmt_usuarios->close();
    
        // Devolver los resultados en formato JSON
        return $resultados;
    }
    
    public function getMisPedidos($id_usuario_comprador, $fecha_inicio, $fecha_fin, $id_categoria) {
        $usuario = new Usuario();
        $misPedidos = array();
        $misPedidos["items"] = array();
    
        // Inicializar la consulta básica
        $query = "
            SELECT 
                pv.fecha_venta AS fecha_compra,
                c.nombre_categoria AS categoria,
                p.nombre_producto AS producto,
                pv.precio AS precio,
                p.valoracion AS valoracion
            FROM 
                Productos_vendidos pv
            JOIN 
                Ventas v ON pv.id_venta = v.id_venta
            JOIN 
                Productos p ON pv.id_producto = p.id_producto
            JOIN 
                Categorias c ON p.id_categoria = c.id_categoria
            WHERE 
                v.id_usuario_comprador = ?
        ";
    
        // Agregar el filtro de fecha si están presentes
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $query .= " AND pv.fecha_venta BETWEEN ? AND ?";
        }
    
        // Agregar el filtro de categoría si no está vacío
        if ($id_categoria != "") {
            $query .= " AND p.id_categoria = ?";
        }
    
        // Ordenar por fecha de venta
        $query .= " ORDER BY pv.fecha_venta DESC";
    
        // Preparar la consulta
        $stmt = $usuario->conexion->prepare($query);
    
        // Bind de los parámetros
        if (!empty($fecha_inicio) && !empty($fecha_fin) && $id_categoria != "") {
            $stmt->bind_param("isss", $id_usuario_comprador, $fecha_inicio, $fecha_fin, $id_categoria);
        } elseif (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $stmt->bind_param("iss", $id_usuario_comprador, $fecha_inicio, $fecha_fin);
        } elseif ($id_categoria != "") {
            $stmt->bind_param("is", $id_usuario_comprador, $id_categoria);
        } else {
            $stmt->bind_param("i", $id_usuario_comprador);
        }
    
        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Verificar los resultados y almacenarlos en el arreglo
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'fecha_compra' => $row['fecha_compra'],
                    'categoria' => $row['categoria'],
                    'producto' => $row['producto'],
                    'precio' => $row['precio'],
                    'valoracion' => $row['valoracion']
                );
                array_push($misPedidos['items'], $item);
            }
        } else {
            $misPedidos['items'] = []; 
        }
    
        return $misPedidos; 
    }
    
    public function obtenerVentasDetalladas($id_vendedor, $fecha_inicio = null, $fecha_fin = null, $categoria = null) {
        $usuario = new Usuario();
        $ventasDetalladas = array();
        $ventasDetalladas["items"] = array();
    
        // Consulta básica
        $query = "
            SELECT 
                pv.fecha_venta, 
                c.nombre_categoria, 
                p.nombre_producto, 
                p.valoracion, 
                p.precio, 
                p.cantidad_disponible
            FROM 
                Productos_vendidos pv
            JOIN 
                Productos p ON pv.id_producto = p.id_producto
            JOIN 
                Categorias c ON p.id_categoria = c.id_categoria
            WHERE 
                p.id_vendedor = ?";
    
        $params = [$id_vendedor];
        $types = "i"; 
    
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $query .= " AND pv.fecha_venta BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
            $types .= "ss"; // Tipo de dato para fechas
        }
        if ($categoria !== null && $categoria !== "") {
            $query .= " AND p.id_categoria = ?";
            $params[] = $categoria;
            $types .= "i"; // Tipo de dato para categoría
        }
    
        // Ordenar por fecha de venta
        $query .= " ORDER BY pv.fecha_venta";
    
        // Preparar la consulta
        $stmt = $usuario->conexion->prepare($query);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $usuario->conexion->error);
        }
    
        $stmt->bind_param($types, ...$params);
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'fecha_venta' => $row['fecha_venta'] ?? "No disponible",
                    'nombre_categoria' => $row['nombre_categoria'] ?? "No disponible",
                    'nombre_producto' => $row['nombre_producto'] ?? "No disponible",
                    'valoracion' => $row['valoracion'] ?? "No disponible",
                    'precio' => $row['precio'] ?? "No disponible",
                    'cantidad_disponible' => $row['cantidad_disponible'] ?? "No disponible"
                );
                array_push($ventasDetalladas['items'], $item);
            }
        } else {
            echo "No se encontraron resultados para los filtros aplicados.";
        }
    
        return $ventasDetalladas;
    }
    
    public function obtenerVentasAgrupadas($id_vendedor, $fecha_inicio = null, $fecha_fin = null, $categoria = null) {
        $usuario = new Usuario();
        $ventasAgrupadas = array();
        $ventasAgrupadas["items"] = array();
    
        $query = "
            SELECT 
                DATE_FORMAT(pv.fecha_venta, '%M-%Y') AS mes_ano, 
                c.nombre_categoria, 
                SUM(pv.precio * pv.cantidad) AS ventas_totales
            FROM 
                Productos_vendidos pv
            JOIN 
                Productos p ON pv.id_producto = p.id_producto
            JOIN 
                Categorias c ON p.id_categoria = c.id_categoria
            WHERE 
                p.id_vendedor = ?";
    
        $params = [$id_vendedor];
        $types = "i";
    
        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $query .= " AND pv.fecha_venta BETWEEN ? AND ?";
            $params[] = $fecha_inicio;
            $params[] = $fecha_fin;
            $types .= "ss"; // Tipo de dato para fechas
        }
      
        if ($categoria !== null && $categoria !== "") {
            $query .= " AND p.id_categoria = ?";
            $params[] = $categoria;
            $types .= "i"; 
        }
    
        $query .= " GROUP BY mes_ano, c.nombre_categoria ORDER BY mes_ano";
    
        $stmt = $usuario->conexion->prepare($query);
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $usuario->conexion->error);
        }
    
        $stmt->bind_param($types, ...$params);
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = array(
                    'mes_ano' => $row['mes_ano'],
                    'categoria' => $row['nombre_categoria'],
                    'ventas_totales' => $row['ventas_totales']
                );
                array_push($ventasAgrupadas['items'], $item);
            }
        }
    
        return $ventasAgrupadas;
    }
    function VerUsuario($id) {
        $usuario = new Usuario();
        $usuarios = array();
        $usuarios["items"] = array();
    
      
        $database = new Database();
        $conexion = $database->getConnection();
    
        $query = "SELECT * FROM Usuarios WHERE id_usuario = ?";
    
        if ($stmt = $conexion->prepare($query)) {
            $stmt->bind_param("i", $id);  
    
            $stmt->execute();
            
            $res = $stmt->get_result();
    
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $item = array(
                        'nombre_usuario' => $row['nombre_usuario'],
                        'correo' => $row['correo'],
                        'sexo' => $row['sexo'],
                        'avatar' => $row['avatar'],
                        'fecha_nacimiento' => $row['fecha_nacimiento'],
                        'rol' => $row['id_rol'],
                        'publico_privado' => $row['publico_privado'],
                    );
                    array_push($usuarios['items'], $item);
                }
                return $usuarios;
            } else {
              
                return array('mensaje' => 'Usuario no encontrado');
            }
    
            $stmt->close();
        } else {
      
            return array('mensaje' => 'Error en la consulta');
        }
    
        $conexion->close();
    }
    public function verificarConversacion($id_comprador, $id_vendedor, $id_producto) {
        $database = new Database();
        $conexion = $database->getConnection();

        $query = "SELECT * FROM Conversaciones WHERE id_comprador = ? AND id_vendedor = ? AND id_producto = ? AND estado_conversacion = 'activa'";

        if ($stmt = $conexion->prepare($query)) {
            $stmt->bind_param("iii", $id_comprador, $id_vendedor, $id_producto);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                return true; 
            } else {
                return false; 
            }

            $stmt->close();
        } else {
            return array('mensaje' => 'Error en la consulta');
        }

        $conexion->close();
    }

    public function crearConversacion($id_comprador, $id_vendedor, $id_producto) {
        $database = new Database();
        $conexion = $database->getConnection();

        $query = "INSERT INTO Conversaciones (id_vendedor, id_comprador, id_producto, estado_conversacion) VALUES (?, ?, ?, 'activa')";

        if ($stmt = $conexion->prepare($query)) {
            $stmt->bind_param("iii", $id_vendedor, $id_comprador, $id_producto);
            if ($stmt->execute()) {
                return true; 
            } else {
                return array('mensaje' => 'Error al crear la conversación');
            }

            $stmt->close();
        } else {
            return array('mensaje' => 'Error en la consulta');
        }

        $conexion->close();
    }

   public function obtenerConversaciones($id_usuario, $es_vendedor = false) {
    $conversaciones = array();
    $database = new Database();
    $conexion = $database->getConnection();

    $query = $es_vendedor
        ? "SELECT c.id_conversacion, c.id_producto, p.nombre_producto, c.id_comprador, u.nombre_usuario, u.avatar
           FROM Conversaciones c
           JOIN Productos p ON c.id_producto = p.id_producto
           JOIN Usuarios u ON c.id_comprador = u.id_usuario
           WHERE c.id_vendedor = ? AND c.estado_conversacion = 'activa'"
        : "SELECT c.id_conversacion, c.id_producto, p.nombre_producto, c.id_vendedor, u.nombre_usuario, u.avatar
           FROM Conversaciones c
           JOIN Productos p ON c.id_producto = p.id_producto
           JOIN Usuarios u ON c.id_vendedor = u.id_usuario
           WHERE c.id_comprador = ? AND c.estado_conversacion = 'activa'";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $conversacion = array(
                'id_conversacion' => $row['id_conversacion'],
                'id_producto' => $row['id_producto'],
                'nombre_producto' => $row['nombre_producto'],
                'otro_usuario' => $row['nombre_usuario'],
                'avatar_otro_usuario' => $row['avatar']
            );

            if ($es_vendedor) {
                $conversacion['id_comprador'] = $row['id_comprador'];
            }

            $conversaciones[] = $conversacion;
        }
    }

    $conexion->close();
    return $conversaciones;
}

function obtenerConversacionesVendedor($id_vendedor) {
    $conversaciones = array();
    $conversaciones["items"] = array();

    $database = new Database();
    $conexion = $database->getConnection();

    $query = "
        SELECT c.id_conversacion, c.id_producto, p.nombre_producto, u.nombre_usuario, u.avatar
        FROM Conversaciones c
        JOIN Productos p ON c.id_producto = p.id_producto
        JOIN Usuarios u ON c.id_comprador = u.id_usuario
        WHERE c.id_vendedor = ? AND c.estado_conversacion = 'activa'
    ";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                array_push($conversaciones["items"], $row);
            }
            echo "<pre>";
            print_r($conversaciones); 
            echo "</pre>";

            return $conversaciones;
        } else {
            return array("mensaje" => "No hay conversaciones activas");
        }

        $stmt->close();
    } else {
        return array("mensaje" => "Error en la consulta");
    }

    $conexion->close();
}
public function updateLista($id_lista, $nombre_lista, $descripcion, $publica_privada) {

    $database = new Database();
    $conexion = $database->getConnection();
    try {
        $query = "UPDATE Listas SET nombre_lista = ?, descripcion = ?, publica_privada = ? WHERE id_lista = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssii", $nombre_lista, $descripcion, $publica_privada, $id_lista);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

public function getListaById($id_lista) {
    $database = new Database();
    $conexion = $database->getConnection();
    try {
        $query = "SELECT * FROM Listas WHERE id_lista = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_lista);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } catch (Exception $e) {
        return null;
    }
}



function getProductosbyCategoria($id_categoria) {
    $usuario = new Usuario();
    $productos = array();
    $productos["items"] = array();

    $query = "SELECT 
                p.*, 
                c.contenido AS imagen, 
                u.nombre_usuario AS nombre_vendedor
            FROM 
                Productos p
            LEFT JOIN 
                Contenido c 
                ON p.id_producto = c.id_producto 
                AND c.tipo_contenido LIKE 'image/%'
                AND c.id_contenido = (
                    SELECT MIN(id_contenido) 
                    FROM Contenido 
                    WHERE id_producto = p.id_producto 
                    AND tipo_contenido LIKE 'image/%'
                )
            LEFT JOIN 
                Usuarios u
                ON p.id_vendedor = u.id_usuario
            WHERE 
                p.id_categoria = ?;";

    $stmt = $usuario->conexion->prepare($query);
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $item = array(
                'id_producto' => $row['id_producto'],
                'nombre_producto' => $row['nombre_producto'],
                'descripcion' => $row['descripcion'],
                'precio' => $row['precio'],
                'cantidad_disponible' => $row['cantidad_disponible'],
                'valoracion' => $row['valoracion'],
                'comentarios' => $row['comentarios'],
                'id_categoria' => $row['id_categoria'],
                'id_vendedor' => $row['id_vendedor'],
                'vendedor' => $row['nombre_vendedor'],

                'estado' => $row['estado'],
                'autorizado' => $row['autorizado'],
                'imagen' => base64_encode($row['imagen']) // Convierte la imagen a base64
            );
            array_push($productos['items'], $item);
        }
    }
    return $productos; 
}


    function exito($mensaje){
        echo json_encode(array('mensaje' => $mensaje));
    }

    function printJSON($array) {
        echo '<code>' . json_encode($array) . '</code>';
    }

    function error($mensaje) {
        echo '<code>' . json_encode(array('mensaje' => $mensaje)) . '</code>';
    }
}

?>
