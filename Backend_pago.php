<?php
//codigo para el backend para pago del modulo seleccion de plan
// corresponde a la HU-002: Seleccionar plan
session_start();
include "conexion.php";

if(!isset($_SESSION['Id_usuario'])){
    echo "Error: No se ha iniciado sesión";
    exit();
}

$id_usuario = $_SESSION['Id_usuario'];

$sql_plan = "Select Id_plan from Usuario Where Id_usuario = '$id_usuario'"; /* Consulta para obtener el id del plan que selecciono el usuario */

$resultado = $conexion -> query($sql_plan);

if( $resultado -> num_rows > 0){
if($fila = $resultado -> fetch_assoc()){
    $id_plan = $fila['Id_plan'];
}
}else{
    echo "Debe de seleccionar un plan antes de pagar";
    exit();
}

 

$nombre_tarjeta = $_POST['nombre_tarjeta'];
$numero_tarjeta = $_POST['numero_tarjeta'];
$fecha_vencimiento = $_POST['fecha_vencimiento'];
$codigo_seguridad = $_POST['codigo_seguridad'];

$Nombre_tarjeta = trim($nombre_tarjeta);
$Numero_tarjeta = trim($numero_tarjeta);
$Codigo_seguridad = trim($codigo_seguridad);

$patron1 = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/";
$patron2 = "/^\d{16}$/";
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

if(!preg_match($patron4, $Codigo_seguridad)){
    echo "Solamente debe ingresar 3 o 4 digitos que tambien deben ser nuemeros enteros";
    exit();
}


$sql = "insert into Pago(Nombre_en_tarjeta, Numero_tarjeta, MM_AA, Codigo_Seguridad, Id_usuario, Id_Plan)
values ('$Nombre_tarjeta', '$Numero_tarjeta', '$fecha_vencimiento', '$Codigo_seguridad', '$id_usuario', '$id_plan')";

if($conexion ->query($sql) === TRUE){
    header("Location: Databridge.html");
    exit();
}else{
    echo "Error al realizar el pago: " . $conexion->error;
    exit();
}
?>