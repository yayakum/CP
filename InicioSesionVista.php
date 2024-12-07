<?php
include_once 'apiUsuarios.php';
include 'navbar.php';


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <link rel="stylesheet" href="styles.css">
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    
    <?php if(isset($_GET['error']) && $_GET['error'] == 1): ?>
    <div class="alert alert-danger" role="alert">
        Credenciales incorrectas. Por favor, intente nuevamente.
    </div>
<?php endif; ?>
   <div class="container mb-3">
    <h3>Empieza a comprar o vender con tu cuenta de BuyHub</h3>
</div>
<div class="container mt-3">
    <div class="flex-container">
        <img src="https://cdn-icons-png.flaticon.com/512/682/682385.png" alt="Study Kit" class="inline-image">
    </div>
</div> 



<form action="InicioSesion.php" method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label custom-label">Email</label>
        <input type="email" class="form-control" id="exampleInputEmail1" name="correo" aria-describedby="emailHelp" 
            value="<?php echo isset($_COOKIE['correo']) ? htmlspecialchars($_COOKIE['correo']) : ''; ?>">
        <div id="emailHelp" class="form-text">No compartiremos tu información con nadie más.</div>
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label custom-label">Contraseña</label>
        <input type="password" class="form-control" id="exampleInputPassword1" name="contrasena" 
            value="<?php echo isset($_COOKIE['contrasena']) ? htmlspecialchars($_COOKIE['contrasena']) : ''; ?>">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="recordarme"
            <?php echo isset($_COOKIE['correo']) ? 'checked' : ''; ?>>
        <label class="form-check-label" for="exampleCheck1">Recordarme</label>
    </div>
    <button type="submit" class="btn btn-primary" id="btn-Entrar">Entrar</button>
</form>


<br> <br> <br> <br> <br> <br> <br>

  <hr class="divider-A-img">

</body>
</html>