<?php
include_once("configuracion.php");

//UTILIZAMOS LOS DATOS INTRODUCIDOS EN EL LOGIN PARA PODER ACCEDER A LA BASE DE DATOS Y ASI LOGEARSE
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email= $_POST["email"];
    $password= $_POST["pass"];

    $pass= md5($password);

    $sql= "SELECT nombre, rol FROM usuarios WHERE email= '$email' AND password= '$pass'";
    $result= $conn->query($sql);

    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();

        $_SESSION["nombre"]= $row["nombre"];
        $_SESSION["rol"]= $row["rol"];

        //QUE NOS REDIRIGA A HOME
        header("Location: home.php");
        exit();
    }
    else
    {
        echo "Error de autenticación.";
    }
}
?>