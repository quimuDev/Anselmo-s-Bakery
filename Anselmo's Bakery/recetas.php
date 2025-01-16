<?php
include_once("configuracion.php");

session_start();

//CREAMOS LA CONSULTA PARA OBTENER TODOS LOS DATOS QUE VAMOS A PINTAR EN LA TABLA 
$sql = "SELECT p.idProducto AS Id, p.nombre AS Nombre, t.nombre AS Tipo FROM producto p JOIN tipo t ON p.idTipo = t.idTipo";
$result = $conn->query($sql);

$sql2 = "SELECT * FROM tipo";
$result2 = $conn->query($sql2);

//CREAMOS LA TABLA
$tabla = "";

if($result->num_rows > 0){
    $tabla = "<div class='container d-flex justify-content-center mt-5'>";
    $tabla .= "<div class='card shadow-sm w-100' style='max-width: 700px;'>";

    $tabla .= "<div class='card-body p-0'>"; 
    $tabla .= "<table class='table table-striped mb-0'>"; 
    $tabla .= "<thead><tr>"; 

    $fields = $result->fetch_fields();
    foreach($fields as $field){
        $tabla .= "<th scope='col' class='bg-warning text-center'>". htmlspecialchars($field->name). "</th>";
    }

    //NOS ASEGURAMOS QUE LA PERSONA QUE HA ACCEDIDO ES ANSELMO Y ASI PODRA TENER VISIBLE LA COLUMNA ELIMINAR
    if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] == "Anselmo"){
        $tabla .= "<th scope='col' class='bg-warning text-center'>Eliminar</th>";
    }

    $tabla .= "</tr></thead>";
    $tabla .= "<tbody>";

    while($row = $result->fetch_assoc()){
        $tabla .= "<tr>";

        //RECORRO UN BUCLE PARA CAMBIAR EL ID POR UN EMOJI ILUSTRATIVO
        foreach ($row as $key => $value){
            if ($key == 'Tipo') {
                switch($value) {
                    case 'pan':
                        $value = "🥖";  
                        break;
                    case 'bollo':
                        $value = "🧁";  
                        break;
                    case 'pastel':
                        $value = "🍰";  
                        break;
                    default:
                        $value = htmlspecialchars($value);
                        break;
                }
            }

            $tabla .= "<td class='text-center'>". htmlspecialchars($value). "</td>";
        }

        //NOS ASEGURAMOS QUE LA PERSONA LOGEADA SEA ANSELMO Y ASI LE APARECEN LOS CAMPOS DE ELIMINAR EN LA TABLA
        if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] == "Anselmo"){
            $tabla .= "<td class='text-center'><a href='eliminarReceta.php?delIdProducto=" . $row['Id'] . "' class='btn btn-danger btn-sm'>X</a></td>";
        }

        $tabla .= "</tr>";
    }

    $tabla .= "</tbody>"; 
    $tabla .= "</table>"; 
    $tabla .= "</div></div></div>";
}


$selectTipo = "<select name='tipo' id='tipo' required>";
$selectTipo.= "<option value='' disabled selected>Selecciona un tipo</option>";

if($result2->num_rows > 0){
    while($row = $result2->fetch_assoc()){
        $selectTipo.= "<option name='tipo' value='" . $row['idTipo'] . "'>" . $row['nombre'] . "</option>";
    }
}

$selectTipo .= "</select>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Recetas</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-warning justify-content-between shadow-sm">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a class="nav-link" style="font-size: 2rem;" href="home.php">🍞</a>
                <a class="nav-link" style="font-size: 1.3rem;" href="cocinar.php"><span style="font-size: 2rem;">👨🏻‍🍳</span>Cocinar</a>
                <a class="btn btn-danger p-1 rounded" href="cerrarSesion.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- SOLO LES APARECERA A LOS ADMINS GRACIAS AL CONDICIONAL QUE ESTAMOS USANDO -->
    <?php if ((isset($_SESSION["rol"]) && ($_SESSION["rol"] == "1"))) { ?>
        <div class="container d-flex justify-content-center align-items-center mt-5 mb-5">
            <div class="card shadow" style="width: 300px;">
                <div class="bg-warning text-dark text-center p-3 rounded-top">
                    <h5>🍞 Recetas de Anselmo 🍞</h5>
                </div>
                <div class="card-body">
                    <form action="recetasInsertar.php" method="POST">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Introduce el nombre" required/>

                        <label for="tipo" class="form-label mt-3">Tipo</label>
                        <?php echo str_replace('<select', '<select required class="form-select"', $selectTipo); ?>

                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-warning">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php echo "<div class='mb-5'>";
    echo $tabla; 
    echo "</div>"; ?>

    

</body>
</html>
