<?php
// Codigo para el backend de seleccion de plan
// Corresponde a la HU-002: Seleccionar plan
session_start();
include "conexion.php";

if(!isset($_SESSION['Id_usuario'])){
    echo "Error: No se ha iniciado sesión";
    exit();
}

$Id_usuario = $_SESSION['Id_usuario'];

$plan_seleccionado = $_POST['Plan'];

if($plan_seleccionado == "Plan Premium"){
    $id_plan = 1;
    $destino = "pago.html";
}else
if($plan_seleccionado == "Plan gratis"){
    $id_plan = 2;
    $destino = "Databridge.html";
}else{
echo "Error: Plan seleccionado no válido";
exit();
}

 $Sql= "update Usuario set Id_plan = $id_plan  where Id_usuario = $Id_usuario";

if($conexion ->query($Sql) === TRUE){
    header("Location: $destino");
    exit();
}else{
    echo "Error al actualizar el plan: " . $conexion->error;
}

?>