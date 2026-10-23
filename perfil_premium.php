<?php
session_start();
include "conexion.php";

if(!isset($_SESSION['Id_usuario'])){
    echo "Debe iniciar sesion";
    exit();
}

$Id_Usuario = $_SESSION['Id_usuario'];

$sql = "select U.Nombre_Usuario, U.Correo_Empresarial, U.Nombre, U.Apellido, P.Nombre_Plan from usuario U
         Join plan_usuario P on U.Id_plan = P.Id_plan where U.Id_usuario = '$Id_Usuario'";
$consulta = $conexion -> query($sql);

    if($consulta && $consulta -> num_rows > 0){
        $fila = $consulta -> fetch_assoc();
        $nombre = $fila['Nombre'];
        $apellido = $fila['Apellido'];
        $nombre_usuario = $fila['Nombre_Usuario'];
        $correo = $fila['Correo_Empresarial'];
        $tipo_plan = $fila['Nombre_Plan'];
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
    <body class = "premium"> 
         <header class = "cabezera">
                <h1> Perfil del Usuario </h1>
                </header>
        <div class="perfil">
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
            <br>
            <h3> Tipo de plan: </h3>
            <?php
            echo $tipo_plan;
            ?>
            <br><br>
            <a href = "cerrar_sesion.php"> <button type="button" class="botonNavbar"> Cerrar Sesión </button> </a>
</div  >
    </body>
</html>

