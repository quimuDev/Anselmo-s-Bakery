<?php
include_once("configuracion.php"); 

//LE DAMOS EL IDPRODUCTO PARA QUE EN ESTA HOJA HAGA EL PREPARED STATEMENT Y ASI SE ELIMINE DE LA TABLA EL QUE ELEGIDO
if (isset($_GET["delIdProducto"])) {
    $delIdProducto = $_GET["delIdProducto"];

    $stmt = $conn->prepare("DELETE FROM producto WHERE idProducto = ?");
    $stmt->bind_param("i", $delIdProducto); 
    $stmt->execute();

    $stmt->close();
    $conn->close();

    //NOS DEVUELVE A RECETAS.PHP
    header("Location: recetas.php");
    exit(); 
}
?>
