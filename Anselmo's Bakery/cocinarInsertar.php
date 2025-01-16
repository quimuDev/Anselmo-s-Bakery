<?php
include_once("configuracion.php");

//OBTENEMOS LOS VALORES DEL POST

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $coste= $_POST["coste"];
    $cantidad= $_POST["cantidad"];
    $costeTotal= $coste*$cantidad;
    $fecha= date("d-m-Y");
    $producto= $_POST["producto"];

    //HACEMOS UN PREPARED STATEMENT PARA CREAR EL REGISTRO NUEVO CON DICHOS DATOS OBTENIDOS MEDIANTE EL POST
    $sql= "INSERT INTO stock (coste, cantidad, costeTotal, fecha, idProducto)
    VALUES (?, ?, ?, ?, ?)";
    $stmt= $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("didsi", $coste, $cantidad, $costeTotal, $fecha, $producto);
        
        if ($stmt->execute()) {
            header("Location: cocinar.php");
            exit();
        } else {
            echo "Error al añadir el producto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
    $conn->close();
}
?>