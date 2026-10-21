<?php
session_start();
include "conexion.php";

if(!isset($_SESSION['Id_usuario'])){
    echo "Debe iniciar sesion";
    exit();
}

$Id_Usurario = $_SESSION['Id_usuario'];

$sql = "select Nombre_Usuario, Correo_Empresarial, Nombre, Apellido from Usuario where Id_usuario = '$Id_Usurario'";
$consulta = $conexion -> query($sql);

    if($consulta && $consulta -> num_rows > 0){
        $fila = $consulta -> fetch_assoc();
        $nombre = $fila['Nombre'];
        $apellido = $fila['Apellido'];
        $nombre_usuario = $fila['Nombre_Usuario'];
        $correo = $fila['Correo_Empresarial'];
    }else{
        echo "no hay valores en los campos de la tabla usuario";
    }
?>

<!doctype html>
<html> 
    <head>
        <meta charset="utf-8">
        <title>Databridge</title>
        <link rel="stylesheet" href="Estilo.css">
        <link rel="icon" href="Databridge.jpeg" type="jpeg">
    </head>
    <body> 
        <div class="perfil">
            <h1> Perfil del Usuario </h1>
            <br>
            <h3> Nombres: </h3>
            <?php
            echo $nombre;
            ?>
            <br>
            <h3> Apellidos: </h3>
            <?php
            echo $apellido;
            ?>
            <br>
            <h3> Nombre de usuario: </h3>
            <?php
            echo $nombre_usuario;
            ?>
            <br>
            <h3> Correo empresarial: </h3>
            <?php
            echo $correo;
            ?>
            <br><br>
            <a href = "inicio_sesion.html"> <button type="button" class="botonNavbar"> Cerrar Sesión </button> </a>
</div>
    </body>
</html>

