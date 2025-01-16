<?php
include_once("configuracion.php"); 

//LE DAMOS EL IDSTOCK PARA QUE EN ESTA HOJA HAGA EL PREPARED STATEMENT Y ASI SE ELIMINE DE LA TABLA EL QUE ELEGIDO
if (isset($_GET["delIdStock"])) {
    $delIdStock = $_GET["delIdStock"];

    $stmt = $conn->prepare("DELETE FROM stock WHERE idStock = ?");
    $stmt->bind_param("i", $delIdStock); 
    $stmt->execute();

    $stmt->close();
    $conn->close();

    //NOS DEVUELVE A COCINAR.PHP
    header("Location: cocinar.php");
    exit(); 
}
?>