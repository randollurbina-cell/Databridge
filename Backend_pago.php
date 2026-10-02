<?php
//codigo para el backend para pago del modulo seleccion de plan
// corresponde a la HU-002: Seleccionar plan
session_start();
include "conexion.php";

if(!isset($_SESSION['Id_usuario'])){
    echo "Error: No se ha iniciado sesión";
    exit();
}


if(!isset($_SESSION["id_plan"])){
    echo "Debe de seleccionar un plan antes de pagar";
    exit();
}

$id_usuario = $_SESSION['Id_usuario']; /* esta variable conntiene el id del usuario que inicio sesion y poder
consutar a la base de datos si este ha adquierido plan, de no ser asi se le muestra mensaje de que no ha 
seleccionado plan */

$id_plan_seleccionado = $_SESSION["id_plan"];   /* esta variable contiene el id que el usuario selecciono con premium
esto se hace para evitar que el usuario se salte este proceso y pueda acceder al menu y se registre como
premium sin antes haber pagado*/

$nombre_tarjeta = $_POST['nombre_tarjeta'];
$numero_tarjeta = $_POST['numero_tarjeta'];
$fecha_vencimiento = $_POST['fecha_vencimiento'];
$codigo_seguridad = $_POST['codigo_seguridad'];

$Nombre_tarjeta = trim($nombre_tarjeta);
$Numero_tarjeta = trim($numero_tarjeta);
$Fecha_vencimiento = trim($fecha_vencimiento);
$Codigo_seguridad = trim($codigo_seguridad);

$patron1 = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/";
$patron2 = "/^\d{16}$/";
$patron3 = "/^(0[1-9]|1[0-2])\/\d{2}$/";
$patron4 = "/^\d{3,4}$/";

if(!preg_match($patron1, $Nombre_tarjeta)){
    echo "Ingrese unicamente letras en este campo";
    exit();
}

if(!preg_match($patron2, $Numero_tarjeta)){
    echo "Debe ingresar unicamente la cantidad de numeros  exactos de la tarjeta que
     ademas deben ser enteros";
    exit();
}

if(!preg_match($patron3, $Fecha_vencimiento)){
    echo "Ingrese la fecha de vencimiento en el formato MM/AA y meses validos";
    exit();
}

if(!preg_match($patron4, $Codigo_seguridad)){
    echo "Solamente debe ingresar 3 o 4 digitos que tambien deben ser nuemeros enteros";
    exit();
}

/* Validar que la fecha en tarjeta no este vencida */

$partes = explode("/", $Fecha_vencimiento);

$mes_tarjeta = (int)$partes[0];
$anio_tarjeta = (int)$partes[1];

$mes_actual  = (int)date("m");
$anio_actual = (int)date("y");

if($anio_tarjeta < $anio_actual){
    echo "La tarjeta es invalida, vencio el Año:".$anio_tarjeta;
    exit();
}elseif($anio_tarjeta == $anio_actual && $mes_tarjeta < $mes_actual){
    echo "Tarjeta invalida la tarjeta vencio este año en el mes:".$mes_tarjeta;
    exit();
}

$sql = "insert into Pago(Nombre_en_tarjeta, Numero_tarjeta, MM_AA, Codigo_Seguridad, Id_usuario, Id_Plan)
values ('$Nombre_tarjeta', '$Numero_tarjeta', '$Fecha_vencimiento', '$Codigo_seguridad', '$id_usuario', '$id_plan_seleccionado')";

if($conexion ->query($sql) === TRUE){
    $Sql2 = "update Usuario set Id_plan = $id_plan_seleccionado  where Id_usuario = $id_usuario";
    $conexion -> query($Sql2);
    header("Location: Databridge_premium.html");
    exit();
}else{
    echo "Error al realizar el pago: " . $conexion->error;
    exit();
}
?>