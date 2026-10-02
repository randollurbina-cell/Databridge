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

/* validar plan seleccionador por usuario, si selecciona gratis, se actualiza a la DB y se dirije a su menu correspondiente, 
de lo contrario si selecciona premium no se actualiza nada en la BD y se le manda a la pasarela de pago, para que pueda
acceder a la interfaz premium */

if($plan_seleccionado == "Plan gratis"){
    $id_plan = 2;

 $Sql= "update Usuario set Id_plan = $id_plan  where Id_usuario = $Id_usuario";
 
if($conexion ->query($Sql) === TRUE){
    header("Location: Databridge.php");
    exit();
}else{
    echo "Error al actualizar el plan: " . $conexion->error;
}

}elseif($plan_seleccionado == "Plan Premium"){
    $id_plan = 1;
    $_SESSION["id_plan"] = "$id_plan";
    header("Location: Pago.html");
    exit();

}else{
echo "Error: Plan seleccionado no válido";
exit();
}

?>