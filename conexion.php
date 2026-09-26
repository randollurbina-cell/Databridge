<?php
// Este codigo corresponde a la conexion con la base de datos, en este caso se utiliza MySQLi para conectarse a una base de datos llamada "Databridge" en un servidor local con el usuario "root" y sin contraseña. Si la conexión falla, se mostrará un mensaje de error.//

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$db = "Databridge";    // "db" es la variable para el nombre de la base de datos

$conexion = new mysqli($servidor, $usuario, $contrasena, $db);

if ($conexion -> connect_error){
    die("Error de conexion:" . $conexion -> connect_error);
}
?>