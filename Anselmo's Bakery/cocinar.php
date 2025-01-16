<?php
include_once("configuracion.php");

session_start();

$sql= "SELECT * FROM stock";
$result= $conn->query($sql);

//CREACIÓN DE LA TABLA
$tabla = "";

if ($result->num_rows > 0) {
    $tabla = "<div class='container d-flex justify-content-center mt-5'>";
    $tabla .= "<div class='card shadow-sm w-100' style='max-width: 700px;'>";

    $tabla .= "<div class='card-body p-0'>"; 
    $tabla .= "<table class='table table-striped mb-0'>"; 
    $tabla .= "<thead><tr>"; 

    $fields = $result->fetch_fields();
    foreach ($fields as $field) {
        $tabla .= "<th scope='col' class='bg-warning text-center'>" . htmlspecialchars($field->name) . "</th>"; 
    }

    //NOS ASEGURAMOS QUE ESTE LOGEADO ANSELMO PARA QUE APAREZCA LA COLUMNA ELIMINAR
    if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] == "Anselmo") {
        $tabla .= "<th scope='col' class='bg-warning text-center'>Eliminar</th>"; 
    }

    $tabla .= "</tr></thead>";
    $tabla .= "<tbody>";

    while ($row = $result->fetch_assoc()) {
        $tabla .= "<tr>";

        foreach ($fields as $field) {
            $tabla .= "<td class='text-center'>" . htmlspecialchars($row[$field->name]) . "</td>"; 
        }

        //NOS ASEGURAMOS QUE ESTE LOGEADO ANSELMO PARA QUE APAREZCA LA COLUMNA DE ELIMINAR, EN ESTE CASO LOS CAMPOS DE LA COLUMNA

        if (isset($_SESSION["nombre"]) && $_SESSION["nombre"] == "Anselmo") {
            $tabla .= "<td class='text-center'><a href='eliminarProducto.php?delIdStock=" . $row['idStock'] . "' class='btn btn-danger btn-sm'>X</a></td>";
        }

        $tabla .= "</tr>";
    }

    $tabla .= "</tbody>"; 
    $tabla .= "</table>";
    $tabla .= "</div></div></div>";
}

//CREACIÓN DEL DESPLEGABLE DE PRODUCTO
$sql2= "SELECT * FROM producto";
$result2= $conn->query($sql2);

$selectProducto = "<select name='producto' id='producto' required>";
$selectProducto.= "<option value='' disabled selected>Selecciona un producto</option>";

if ($result2->num_rows > 0) { 
    while ($row = $result2->fetch_assoc()) {
        $selectProducto .= "<option value='" . $row['idProducto'] . "' data-tipo='".$row['idTipo'] . "'>" . $row['nombre'] . "</option>";
    }
    $selectProducto .= "</select>";
}

// $precioTrigo = 0.5 + (mt_rand() / mt_getrandmax()) * 0.5;
// $precioTrigo = round($precioTrigo, 2);


//CONSULTA PARA OBTENER EL COSTE TOTAL Y UTILIZARLO POSTERIORMENTE COMO CONDICIONAL PARA NO PODER INGRESAR MAS PRODUCTOS POR LIMITE DE PRESUPUESTO
$sql3= "SELECT SUM(costeTotal) AS costeTotalTotal FROM stock";
$result3= $conn->query($sql3);

$costeTotalTotal= 0;
$presupuesto= 1000;

if($result3 && $row = $result3->fetch_assoc()){
    $costeTotalTotal= $row["costeTotalTotal"];
}

//CONSULTA PARA OBTENER LOS PANES ACTUALES QUE HAY EN LA BASE DE DATOS
$sql4= "SELECT SUM(s.cantidad) AS panTotal FROM stock s JOIN producto p ON s.idProducto = p.idProducto WHERE p.idTipo = 1";
$result4= $conn->query($sql4);

if($result4 && $row = $result4->fetch_assoc()){
    $panTotal= $row["panTotal"] ?? 0;
}

//CONSULTA PARA OBTENER LOS BOLLOS ACTUALES QUE HAY EN LA BASE DE DATOS
$sql5= "SELECT SUM(s.cantidad) AS bolloTotal FROM stock s JOIN producto p ON s.idProducto = p.idProducto WHERE p.idTipo = 2";
$result5= $conn->query($sql5);

if($result5 && $row = $result5->fetch_assoc()){
    $bolloTotal= $row["bolloTotal"] ?? 0;
}

//CONSULTA PARA OBTENER LOS PASTELES ACTUALES QUE HAY EN LA BASE DE DATOS
$sql6= "SELECT SUM(s.cantidad) AS pastelTotal FROM stock s JOIN producto p ON s.idProducto = p.idProducto WHERE p.idTipo = 3";
$result6= $conn->query($sql6);

if($result6 && $row = $result6->fetch_assoc()){
    $pastelTotal= $row["pastelTotal"] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Cocinar</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-warning justify-content-between shadow-sm">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a class="nav-link" style="font-size: 2rem;" href="home.php">🍞</a> 
                <a class="nav-link" style="font-size: 1.3rem;" href="recetas.php"><span style="font-size: 2rem;">📖</span>Recetas</a>
                <a class="btn btn-danger p-1 rounded" href="cerrarSesion.php">Cerrar Sesión</a>
            </div>
        </div>
</nav>

<!-- FORMULARIO -->
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow" style="width: 300px;"> 
        <div class="bg-warning text-dark text-center p-3 rounded-top">
            <h5>🍞 Cocina de Anselmo 🍞</h5>
        </div>
        <div class="card-body">
            <form action="cocinarInsertar.php" method="POST">

            <!-- SOLO LES APARECERA LA OPCIÓN DE COCINAR A LOS ADMINS -->
                <?php if ((isset($_SESSION["rol"]) && ($_SESSION["rol"] == "1"))) { ?>
                    <label for="producto" class="form-label">Nombre</label>
                    <?php echo str_replace('<select', '<select required class="form-select form-select-sm"', $selectProducto); ?>

                    <label for="coste" class="form-label">Coste</label>
                    <input type="text" name="coste" id="coste" class="form-control form-control-sm" placeholder="Introduce el coste" required oninput="calcularCoste()">

                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="text" name="cantidad" id="cantidad" class="form-control form-control-sm" placeholder="Introduce la cantidad" required oninput="calcularCoste()">

                    <div id="costeTotal" class="text-center mt-2" style="font-size: 0.9rem;">Coste total: 0.00 €</div>

                    <div class="d-flex justify-content-center mt-2">
                        <button type="submit" class="btn btn-warning btn-sm">Cocinar</button>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger text-center" role="alert" style="font-size: 1rem;">Aquí solo entran el Anselmo y la Hortensia</div>
                <?php } ?>
            </form>
        </div>
    </div>
</div>

<!-- PINTADO DE LA TABLA -->
<?php echo "<div class='mb-5'>";
    echo $tabla; 
    echo "</div>"; ?>

<script>
    //FUNCIÓN PARA OBTENER EL COSTE Y QUE SALTE EL ALERT DE LIMITE DE PRESUPUESTO
    function calcularCoste() {
        let coste = parseFloat($("#coste").val()) || 0;
        let cantidad = parseFloat($("#cantidad").val()) || 0;
        let costeTotal = (coste * cantidad).toFixed(2); 
        let tipo = $('#producto option:selected').data('tipo');
        
        let costeTotalTotal = <?php echo $costeTotalTotal; ?>;  
        let presupuesto = <?php echo $presupuesto; ?>;  
        
        let limitePresupuesto = parseFloat(costeTotalTotal) + parseFloat(costeTotal); 
        
        $("#costeTotal").text(`Coste total: ${costeTotal} €`);

        if (limitePresupuesto > presupuesto) {
            alert("¡¡ANSELMO TAS PASAO DE PRESUPUESTO!!");
            $("#coste").val(''); 
            $("#cantidad").val('');
            $("#costeTotal").text("Coste total: 0.00 €");
        }

        let panTotal = <?php echo $panTotal; ?>;
        let bolloTotal = <?php echo $bolloTotal; ?>;
        let pastelTotal = <?php echo $pastelTotal; ?>;

        //PARA NO DEJAR INTRODUCIR MÁS DE 20 BARRAS DE PAN
        if(tipo===1){
            if (cantidad + panTotal > 20) {
            alert("¡¡ANSELMO TAS PASAO DE PANES!");
            $("#coste").val(''); 
            $("#cantidad").val('');
            $("#costeTotal").text("Coste total: 0.00 €");
            }
        }
        //PARA NO DEJAR INTRODUCIR MÁS DE 10 BOLLOS
        else if(tipo===2)
        {
            if (cantidad + bolloTotal > 10) {
            alert("¡¡ANSELMO TAS PASAO DE BOLLOS!!");
            $("#coste").val(''); 
            $("#cantidad").val('');
            $("#costeTotal").text("Coste total: 0.00 €");
            }
        }
        //PARA NO DEJAR INTRODUCIR MÁS DE 5 PASTELES
        else if(tipo===3)
        {
            if (cantidad + pastelTotal > 5) {
            alert("¡¡ANSELMO TAS PASAO DE PASTELES!!");
            $("#coste").val(''); 
            $("#cantidad").val('');
            $("#costeTotal").text("Coste total: 0.00 €");
            }
        }
        
    }
    
    //FUNCIÓN PARA QUE SALTE UNA ALERTA EN CASO DE QUE SE EXCEDA DEL 65% DEL PRESUPUESTO
    $(document).ready(function() {
        let costeTotalTotal = <?php echo $costeTotalTotal; ?>;  
        let presupuesto = <?php echo $presupuesto; ?>;  

        if (costeTotalTotal > presupuesto * 0.65) {
            alert("¡Anselmo te tas pasando de presupuesto!");
        }
    });
</script>
</body>
</html>
