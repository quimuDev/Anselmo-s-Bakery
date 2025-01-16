<?php
session_start();

//OBTENEMOS EL NOMBRE DE LA PERSONA QUE HA ACCEDIDO CON EL LOGIN PARA PERSONALIZAR LA VARIABLE BIENVENIDO
if(isset($_SESSION["nombre"])){
    $nombreSignIn= $_SESSION["nombre"];
    $bienvenido="Bienvenid@ ".$nombreSignIn;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Home</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-warning justify-content-between shadow-sm">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a class="nav-link" style="font-size: 2rem;" href="home.php">🍞</a>
                <a class="btn btn-danger p-1 rounded" href="cerrarSesion.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-center align-items-end" style="height: 20vh;">
        <h2 class="display-5">
            <?php echo $bienvenido; ?>
        </h2>
    </div>


    <!--TENDREMOS DOS OPCIONES, COCINAR, QUE ES PARA PREPARAR RECETAS PARA VENDER EN LA TIENDA Y LA OPCION DE RECETAS PARA VER LAS QUE HAY Y/O AÑADIR NUEVAS-->

    <div class="container d-flex flex-column justify-content-center align-items-center" style="height: 60vh;">
        <div class="row w-100">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="card shadow" style="width: 17rem;">
                    <div class="bg-warning text-dark text-center p-3 rounded-top">
                        <h3>Cocinar</h3>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 200px;">
                        <a href="cocinar.php" class="text-decoration-none" style="font-size: 7rem;">👨🏻‍🍳</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex justify-content-center">
                <div class="card shadow" style="width: 17rem;">
                    <div class="bg-warning text-dark text-center p-3 rounded-top">
                        <h3>Recetas</h3>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 200px;">
                        <a href="recetas.php" class="text-decoration-none" style="font-size: 7rem;">📖</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
