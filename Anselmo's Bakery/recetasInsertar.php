<?php
include_once("configuracion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $tipo = $_POST["tipo"];

    $sql = "INSERT INTO producto (nombre, idTipo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $nombre, $tipo);
        
        if ($stmt->execute()) {
            header("Location: recetas.php");
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
